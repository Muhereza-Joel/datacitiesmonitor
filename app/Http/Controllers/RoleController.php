<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class RoleController extends Controller
{

    /**
     * Display a listing of roles.
     */
    public function index()
    {
        $roles = Role::with('permissions')->get();
        return view('roles.index', compact('roles'));
    }

    /**
     * Show the form for editing the specified role.
     */
    public function edit(string $id)
    {
        $role = Role::findOrFail($id);
        $models = $this->getSystemModels();
        $prefixes = ['view_any', 'view', 'create', 'update', 'delete', 'restore', 'force_delete'];

        // Pluck existing assigned permission keys to safely map state in blade lookups
        $assignedPermissions = $role->permissions->pluck('name')->toArray();

        return view('roles.edit', compact('role', 'models', 'prefixes', 'assignedPermissions'));
    }

    /**
     * Update the specified role in storage.
     */
    public function update(Request $request, string $id)
    {
        $role = Role::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:125|unique:roles,name,' . $role->id,
            'permissions' => 'nullable|array',
            'permissions.*' => 'string|exists:permissions,name'
        ]);

        $newName = strtolower(trim($request->name));

        // Prevent renaming a role to 'super-admin' if a super-admin already exists (and it's not the current role)
        if ($newName === 'super-admin' && Role::where('name', 'super-admin')->where('id', '!=', $role->id)->exists()) {
            return redirect()->back()->withErrors(['name' => 'A super-admin role already exists. Only one super-admin is allowed.'])->withInput();
        }

        // Prevent renaming a role to 'admin' if an admin already exists (and it's not the current role)
        if ($newName === 'admin' && Role::where('name', 'admin')->where('id', '!=', $role->id)->exists()) {
            return redirect()->back()->withErrors(['name' => 'An admin role already exists. Only one admin role is allowed.'])->withInput();
        }

        // Protect administrative system handles from changes
        if (!in_array($role->name, ['admin', 'super-admin'])) {
            $role->name = $newName;
        }

        $role->save();

        // Synchronize multi-matrix values cleanly
        $permissions = $request->input('permissions', []);
        $role->syncPermissions($permissions);

        return redirect()->route('roles.index')
            ->with('success', "Role Authorization configurations modified successfully.");
    }

    /**
     * Show form to create roles with dynamically generated permission checkboxes.
     */
    public function create()
    {
        // 1. Get all models in your app folder dynamically
        $models = $this->getSystemModels();

        // 2. Define standard resource actions (Filament Shield style)
        $prefixes = ['view_any', 'view', 'create', 'update', 'delete', 'restore', 'force_delete'];

        // 3. Ensure permissions exist in the database for each discovered resource
        foreach ($models as $model) {
            foreach ($prefixes as $prefix) {
                $permissionName = $prefix . '_' . Str::snake($model);
                Permission::firstOrCreate([
                    'name' => $permissionName,
                    'guard_name' => 'web'
                ]);
            }
        }

        // 4. Fetch all permissions cleanly chunked by resource group
        $allPermissions = Permission::where('guard_name', 'web')->get();

        return view('roles.create', compact('models', 'prefixes', 'allPermissions'));
    }

    /**
     * Store new role and synchronize mapped authorization sets.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:125|unique:roles,name',
            'permissions' => 'nullable|array',
            'permissions.*' => 'string|exists:permissions,name'
        ]);

        $name = strtolower(trim($request->name));

        // Guard: only one super-admin role may exist
        if ($name === 'super-admin' && Role::where('name', 'super-admin')->exists()) {
            return redirect()->back()->withErrors(['name' => 'A super-admin role already exists. Only one super-admin is allowed.'])->withInput();
        }

        // Guard: only one admin role may exist
        if ($name === 'admin' && Role::where('name', 'admin')->exists()) {
            return redirect()->back()->withErrors(['name' => 'An admin role already exists. Only one admin role is allowed.'])->withInput();
        }

        // Create the Spatie access control record
        $role = Role::create([
            'name' => $name,
            'guard_name' => 'web'
        ]);

        if ($request->has('permissions')) {
            $role->syncPermissions($request->permissions);
        }

        return redirect()->route('roles.index')
            ->with('success', "Role '" . ucfirst($role->name) . "' along with its mapped authorization matrix built successfully.");
    }

    /**
     * Scan App Directory to pull all active models
     */
    private function getSystemModels(): array
    {
        $modelPath = app_path('Models');
        if (!File::isDirectory($modelPath)) {
            return [];
        }

        $files = File::files($modelPath);
        $models = [];

        foreach ($files as $file) {
            $filename = $file->getFilenameWithoutExtension();
            // Exclude base or pivot configurations if any exist
            if ($filename !== 'Pivot') {
                $models[] = $filename;
            }
        }

        return $models;
    }

    /**
     * Remove the specified role from storage.
     */
    public function destroy(string $id)
    {
        $role = Role::findOrFail($id);

        if (in_array($role->name, ['admin', 'super-admin'])) {
            return redirect()->route('roles.index')->withErrors(['Core admin handles cannot be deleted.']);
        }

        $role->delete();
        return redirect()->route('roles.index')->with('success', 'Security Role purged from system access logs.');
    }
}
