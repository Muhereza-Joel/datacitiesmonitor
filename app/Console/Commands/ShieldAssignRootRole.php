<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Spatie\Permission\Models\Role;

class ShieldAssignRootRole extends Command
{
    /**
     * The name and signature of the console command.
     * Accepts either a direct UUID string or a fast-track selection.
     *
     * @var string
     */
    protected $signature = 'shield:assign-root {user_id? : The explicit UUID string of the target user}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Select a system user via simple integer indexing or direct UUID and provision the super-admin role';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        // 1. Verify that the root role actually exists first
        $role = Role::where('name', 'super-admin')->first();

        if (!$role) {
            $this->error("The 'super-admin' role does not exist yet. Please execute [php artisan shield:generate-root] first.");
            return 1;
        }

        $selectedUser = null;
        $userIdInput = $this->argument('user_id');

        if ($userIdInput !== null) {
            // Context A: User provided a direct string argument (Treat as explicit UUID check)
            $selectedUser = User::find($userIdInput);

            if (!$selectedUser) {
                $this->error("No user found with database UUID matching: {$userIdInput}");
                return 1;
            }
        } else {
            // Context B: Interactive Wizard Mode (With simple integer indexes to avoid typing UUIDs)
            $users = User::select('id', 'name', 'email')->get();

            if ($users->isEmpty()) {
                $this->error('No users found inside the database logs.');
                return 1;
            }

            // Create two matching maps: one for the choice labels, one to store the real UUIDs
            $menuOptions = [];
            $uuidLookupTable = [];
            $counter = 1;

            foreach ($users as $user) {
                // Use a clean incremental key (1, 2, 3...) instead of the raw UUID string
                $menuOptions[$counter] = "{$user->name} ({$user->email})";
                $uuidLookupTable[$counter] = $user->id; // Stores the actual structural UUID
                $counter++;
            }

            // This outputs a clean list where options are numbered 1, 2, 3...
            $choiceKey = $this->choice(
                'Which user should be promoted to Root / Super-Admin? (Type the simple option number)',
                $menuOptions
            );

            // Resolve the clean index key from the text choice selected by the developer
            $resolvedIndex = array_search($choiceKey, $menuOptions);

            if (!$resolvedIndex || !isset($uuidLookupTable[$resolvedIndex])) {
                $this->error('Invalid index selection parsed.');
                return 1;
            }

            // Extract the real UUID and find the user model instance
            $targetUuid = $uuidLookupTable[$resolvedIndex];
            $selectedUser = User::find($targetUuid);
        }

        if (!$selectedUser) {
            $this->error('Target user account structure could not be verified.');
            return 1;
        }

        // 2. Clear confirmation validation prompt
        $this->line('');
        $this->warn("ATTENTION: You are about to grant global administrative root clearance.");
        if ($this->confirm("Are you sure you want to grant absolute privileges to {$selectedUser->name}?")) {
            $selectedUser->assignRole($role);
            $this->info("✓ Success: {$selectedUser->name} has been assigned the 'super-admin' role.");
        } else {
            $this->warn('Operation cancelled.');
        }

        return 0;
    }
}
