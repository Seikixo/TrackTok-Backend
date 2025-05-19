<?php

namespace Database\Factories;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Appointment>
 */
class AppointmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startTime = $this->faker->time('H:i');
        $endTime = \Carbon\Carbon::parse($startTime)->addHours(1)->format('H:i');

        return [
            'customer_id' => Customer::factory(),
            'appointment_date' => $this->faker->date(),
            'start_time' => $startTime,
            'end_time' => $endTime,
            'total_price' => $this->faker->randomFloat(2, 100, 1000),
            'status' => $this->faker->randomElement(['Scheduled', 'Confirmed', 'Completed', 'Cancelled']),
            'notes' => $this->faker->optional()->sentence(),
        ];
    }
}
