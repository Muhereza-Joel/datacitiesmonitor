<?php

namespace Database\Seeders;

use App\Models\Organisation;
use Illuminate\Database\Seeder;
use Ramsey\Uuid\Uuid;

class OrganisationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create the default Administrator organisation
        Organisation::firstOrCreate(
            ['name' => 'Administrator'],
            ['id' => Uuid::uuid4()->toString(), 'logo' => null],
            ['logo' => 'assets/img/placeholder.png']
        );
    } 
    
}
