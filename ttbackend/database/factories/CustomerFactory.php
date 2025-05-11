<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Customer>
 */
class CustomerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
        'name' => $this->faker->unique()->name(),
        'email' => $this->faker->unique()->safeEmail(),
        'address' => $this->faker->address,
        'contact_number' => '09' . $this->faker->unique()->randomNumber(9, true),
        ];
    }
}
