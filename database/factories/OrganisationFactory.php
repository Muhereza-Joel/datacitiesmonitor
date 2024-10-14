<?php

namespace Database\Factories;

use App\Models\Organisation;
use Illuminate\Database\Eloquent\Factories\Factory;
use Ramsey\Uuid\Uuid;

class OrganisationFactory extends Factory
{
    protected $model = Organisation::class;
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'id' => Uuid::uuid4()->toString(),
            'name' => $this->faker->company, // Example: random company name
            'logo' => $this->faker->imageUrl(), // Example: random image URL
        ];
    }
}
