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

        // 2. Generate permissions for each model chunk
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

        // 3. Synchronize all permissions to the root role
        $role->syncPermissions($permissionNames);

        $this->line('');
        $this->info("✓ Success: 'super-admin' role configured with " . count($permissionNames) . " permissions.");

        return 0;
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
            if ($filename !== 'Pivot') {
                $models[] = $filename;
            }
        }

        return $models;
    }
}
