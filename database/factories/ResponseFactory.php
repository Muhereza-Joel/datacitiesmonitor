<?php

namespace Database\Factories;

use App\Models\Response;
use App\Models\Indicator;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ResponseFactory extends Factory
{
    protected $model = Response::class;

    public function definition()
    {
        // Fetch a random indicator
        $indicator = Indicator::inRandomOrder()->first(); 
        
        // Fetch the latest response for the selected indicator
        $previousResponse = $indicator->responses()->latest()->first();
        
        // Ensure that previousCurrent starts at baseline if no previous response
        $previousCurrent = $previousResponse ? $previousResponse->current : $indicator->baseline;

        // Add a check to prevent current from exceeding target or falling below previousCurrent
        $current = $this->faker->randomFloat(2, $previousCurrent + 0.1, $indicator->target);

        // Calculate progress based on previous and current values
        $progress = ($indicator->target - $indicator->baseline) > 0 
            ? ($current - $previousCurrent) / ($indicator->target - $indicator->baseline) * 100 
            : 0;

        // Return the seeded response data
        return [
            'id' => Str::uuid(),
            'indicator_id' => $indicator->id,
            'current' => $current,
            'progress' => $progress,
            'notes' => $this->faker->sentence(6),
            'lessons' => $this->faker->sentence(8),
            'recommendations' => $this->faker->sentence(5),
            'status' => $this->faker->randomElement(['draft', 'review', 'public', 'archived']),
            'organisation_id' => Str::uuid(),
            'user_id' => Str::uuid(),
        ];
    }
}

