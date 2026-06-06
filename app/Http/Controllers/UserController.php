<?php

namespace App\Http\Controllers;

use App\Models\Organisation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role; // optional, used for validation

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pageTitle = 'Users';
        $currentUser = Auth::user();
        $organisation_id = $currentUser->organisation_id;

        // Check if the current user has the 'root' role (Spatie)
        if ($currentUser->hasRole('super-admin')) {
            $users = User::with(['organisation', 'profile', 'roles'])
                ->withoutSuperAdmin()
                ->paginate(25);
        } else {
            $users = User::with(['organisation', 'profile', 'roles'])
                ->where('organisation_id', $organisation_id)
                ->withoutSuperAdmin()
                ->paginate(25);
        }

        return view('users.list', compact('pageTitle', 'users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $pageTitle = 'Create User';
        $currentUser = Auth::user();
        $organisation_id = $currentUser->organisation_id;
        $myOrganisation = Organisation::findOrFail($organisation_id);
        $roles = Role::whereRaw('LOWER(name) != ?', ['super-admin'])->get();

        return view('users.create', compact('pageTitle', 'myOrganisation', 'roles'));
    }

    public function create_organisation_user()
    {
        $pageTitle = 'Create User';
        $organisations = Organisation::all();
        $roles = Role::whereRaw('LOWER(name) != ?', ['super-admin'])->get();

        return view('users.createOrganisationUser', compact('pageTitle', 'organisations', 'roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'string|required|max:30|unique:users,name',
            'email' => 'string|required|max:50|unique:users,email',
            'role' => 'required|string|exists:roles,name', // role must exist in Spatie roles table
            'password' => 'string|required|min:8',
            'organisation_id' => 'string|required|exists:organisations,id',
        ]);

        // Hash the password
        $validated['password'] = bcrypt($validated['password']);
        $roleName = $validated['role'];
        unset($validated['role']); // remove role from data – we'll assign it via Spatie

        // Create the user
        $user = User::create($validated);

        // Assign the Spatie role
        $user->assignRole($roleName);

        $currentUser = Auth::user();
        $organisation_id = $currentUser->organisation_id;
        $myOrganisation = Organisation::findOrFail($organisation_id);

        return redirect()->back()
            ->with(['success' => 'User Created Successfully', 'myOrganisation' => $myOrganisation]);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $pageTitle = "User Details";
        $userDetails = User::findOrFail($id);
        return view('users.view', compact('pageTitle', 'userDetails'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        // Not implemented
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Not implemented
    }

    /**
     * Remove the specified resource from storage (soft delete).
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json(['message' => 'User deleted successfully.']);
    }

    /**
     * Update a user's role using Spatie.
     */
    public function updateRole(Request $request, $id)
    {
        $validated = $request->validate([
            'role' => 'required|string|exists:roles,name',
        ]);

        $user = User::findOrFail($id);

        // Sync the role (assuming a user has only one role – adjust if multiple are allowed)
        $user->syncRoles([$validated['role']]);

        return response()->json(['message' => 'User role updated successfully.']);
    }

    /**
     * Update a user's organisation.
     */
    public function updateOrganisation(Request $request, $id)
    {
        $validated = $request->validate([
            'organisation_id' => 'string|required|max:36|exists:organisations,id'
        ]);

        $user = User::findOrFail($id);
        $user->organisation_id = $validated['organisation_id'];
        $user->save();

        return response()->json(['message' => 'User organisation updated successfully.']);
    }

    /**
     * Update a user's email address.
     */
    public function updateEmail(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:users,id',
            'email' => 'required|email|unique:users,email,' . $request->id
        ]);

        $user = User::find($request->id);
        $user->email = $request->email;
        $user->save();

        return response()->json(['message' => 'Email updated successfully.']);
    }

    /**
     * Reset a user's password.
     */
    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:users,id',
            'password' => 'required|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = User::findOrFail($request->id);
        $user->password = Hash::make($request->password);
        $user->save();

        return response()->json(['message' => 'Password has been reset successfully.'], 200);
    }
}
