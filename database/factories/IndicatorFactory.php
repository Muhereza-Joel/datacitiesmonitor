<?php

namespace Database\Factories;

use App\Models\Indicator;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class IndicatorFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Indicator::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $baseline = $this->faker->randomFloat(2, 1, 5); // Generate a random baseline
        $target = $this->faker->randomFloat(2, 6, 10);  // Generate a random target higher than baseline

        return [
            'id' => Str::uuid(),
            'category' => 'None',
            'name' => $this->faker->sentence(3),
            'indicator_title' => $this->faker->sentence(3),
            'definition' => $this->faker->paragraph,
            'baseline' => $baseline,
            'target' => $target,
            'current_state' => $this->faker->randomFloat(2, $baseline, $target),
            'data_source' => $this->faker->word,
            'frequency' => $this->faker->randomElement(['Monthly', 'Quarterly', 'Annually']),
            'responsible' => $this->faker->name,
            'reporting' => $this->faker->sentence(3),
            'status' => $this->faker->randomElement(['draft', 'review', 'public', 'archived']),
            'organisation_id' => Str::uuid(),
            'qualitative_progress' => $this->faker->randomElement(['On Track', 'Needs Attention', 'Critical']),
            'is_manually_updated' => false,
        ];
    }
}
