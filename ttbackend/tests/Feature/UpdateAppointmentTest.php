<?php

use App\Models\User;
use App\Models\Customer;
use App\Models\Appointment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use function Pest\Laravel\actingAs;

uses(RefreshDatabase::class);

it('updates an appointment', function () {
    $user = User::factory()->create();
    $customer = Customer::factory()->create();
    $appointment = Appointment::factory()->create([
        'customer_id' => $customer->id,
        'appointment_date' => '2023-10-01',
        'start_time' => '10:00',
        'end_time' => '11:00',
        'total_price' => 100,
        'status' => 'Confirmed',
        'notes' => 'Test appointment',
    ]);

    $payload = [
        'customer_id' => $customer->id,
        'appointment_date' => '2023-10-02',
        'start_time' => '12:00',
        'end_time' => '13:00',
        'total_price' => 200,
        'status' => 'Completed',
        'notes' => 'Updated appointment',
    ];

    $response = actingAs($user, 'sanctum')
        ->putJson("/api/appointments/{$appointment->id}", $payload);

    $response->assertOk()
        ->assertJson([
            'success' => true,
            'message' => 'Appointment updated successfully.',
        ]);

    expect(Appointment::find($appointment->id)->appointment_date)->toBe('2023-10-02');
});