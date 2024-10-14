<?php

namespace Database\Seeders;

use App\Models\Indicator;
use App\Models\Response;
use Illuminate\Database\Seeder;

class IndicatorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create 10 indicators
        $indicators = Indicator::factory()->count(10)->create();

        // Create responses for each indicator
        foreach ($indicators as $indicator) {
            Response::factory()->count(5)->create([
                'indicator_id' => $indicator->id,
            ]);
        }
    }
}
