<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Service>
 */
class ServiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'category_id' => Category::factory(),
            'name' => $this->faker->unique()->word(),
            'description' => $this->faker->sentence(),
            'duration' => $this->faker->numberBetween(30, 120), 
            'price' => $this->faker->randomFloat(2, 10, 500), 
        ];
    }
}
