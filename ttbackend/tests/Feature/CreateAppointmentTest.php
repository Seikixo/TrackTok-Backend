<?php

use App\Models\Customer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\postJson;
use function Pest\Laravel\assertDatabaseHas;

uses(RefreshDatabase::class);

it('creates an appointment', function () {
    // Simulate authenticated user
    $user = User::factory()->create();
    actingAs($user, 'sanctum');

    // Create a customer to associate with the appointment
    $customer = Customer::factory()->create();

    // Prepare test data
    $appointmentData = [
        "customer_id" => $customer->id,
        "appointment_date" => "2025-05-22",
        "start_time" => "08:00",
        "end_time" => "09:30",
        "total_price" => 2800,
        "status" => "Confirmed",
        "notes" => "Customer asked for morning appointment."
    ];

    // Send POST request to the endpoint
    $response = postJson('/api/appointments/', $appointmentData);

    $response->assertCreated()
        ->assertJson([
            'success' => true,
            'message' => 'Appointment created successfully.',
        ]);

    assertDatabaseHas('appointments', [
        'customer_id' => $customer->id,
        'appointment_date' => '2025-05-22',
        'total_price' => 2800,
    ]);
});
