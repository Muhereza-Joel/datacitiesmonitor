<?php

namespace Database\Seeders;

use App\Models\Organisation;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create an organisation or use an existing one
        $organisation = Organisation::firstOrCreate(
            ['name' => 'Administrator'],
            ['logo' => 'assets/img/avatar.png'] // Use the default avatar
        );

        // Seed users
        User::factory()->create([
            'name' => 'Administrator',
            'email' => 'muherezajoel40@gmail.com',
            'role' => 'admin',
            'profile_created' => false,
            'organisation_id' => $organisation->id,
            'password' => bcrypt('Vision60@moels'), // Use bcrypt for password hashing
        ]);

    }
}
