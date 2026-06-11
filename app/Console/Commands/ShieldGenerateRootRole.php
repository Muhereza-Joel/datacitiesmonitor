<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class ShieldGenerateRootRole extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'shield:generate-root';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a super-admin root role and assign all discovered model permissions to it';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Scanning application models...');

        $models = $this->getSystemModels();
        $prefixes = ['view_any', 'view', 'create', 'update', 'delete', 'restore', 'force_delete'];

        if (empty($models)) {
            $this->error('No models found in app/Models directory.');
            return 1;
        }

        // 1. Create or retrieve the super-admin role
        $role = Role::firstOrCreate([
            'name' => 'super-admin',
            'guard_name' => 'web'
        ]);

        $this->info('Ensuring all system permissions exist in the database...');
        $permissionNames = [];

        // 2. Generate permissions for each model chunk (standard CRUD)
        foreach ($models as $model) {
            foreach ($prefixes as $prefix) {
                $permissionName = $prefix . '_' . Str::snake($model);

                Permission::firstOrCreate([
                    'name' => $permissionName,
                    'guard_name' => 'web'
                ]);

                $permissionNames[] = $permissionName;
            }
        }

        // 2b. Extra permissions from config file
        $extraPermissionsMap = config('permission.extra_permissions', []);
        foreach ($extraPermissionsMap as $model => $extras) {
            // Only add extra permissions if the model exists in our discovered models list
            if (!in_array($model, $models)) {
                $this->warn("Model '{$model}' has extra permissions defined but was not found in app/Models. Skipping.");
                continue;
            }

            foreach ($extras as $extra) {
                $permissionName = $extra . '_' . Str::snake($model);

                Permission::firstOrCreate([
                    'name' => $permissionName,
                    'guard_name' => 'web'
                ]);

                $permissionNames[] = $permissionName;
            }
        }

        // 3. Synchronize all permissions (standard + extra) to the root role
        $role->syncPermissions($permissionNames);

        $this->line('');
        $this->info("✓ Success: 'super-admin' role configured with " . count($permissionNames) . " permissions.");

        return 0;
    }

    /**
     * Scan App Directory to pull all active models and include Spatie Role
     */
    private function getSystemModels(): array
    {
        $modelPath = app_path('Models');
        $models = [];

        if (File::isDirectory($modelPath)) {
            $files = File::files($modelPath);

            foreach ($files as $file) {
                $filename = $file->getFilenameWithoutExtension();
                if ($filename !== 'Pivot') {
                    $models[] = $filename;
                }
            }
        }

        // Explicitly inject 'Role' into the system models matrix if not already listed
        if (!in_array('Role', $models)) {
            $models[] = 'Role';
        }

        return $models;
    }
}
