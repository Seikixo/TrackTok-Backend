<?php

use App\Models\User;
use App\Models\Customer;
use App\Models\Appointment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\getJson;

uses(RefreshDatabase::class);

it('fetches a list of appointments with pagination, sorting, filter, and searching', function () {
    // Create and authenticate a user
    $user = User::factory()->create();
    actingAs($user, 'sanctum');

    // Create customers
    $john = Customer::factory()->create(['name' => 'John Doe']);
    $jane = Customer::factory()->create(['name' => 'Jane Smith']);

    // Create appointments
    Appointment::factory()->create([
        'customer_id' => $john->id,
        'appointment_date' => '2023-10-01',
        'start_time' => '10:00',
        'end_time' => '11:00',
        'total_price' => 100,
        'status' => 'Confirmed',
        'notes' => 'Test appointment 1',
    ]);

    Appointment::factory()->create([
        'customer_id' => $jane->id,
        'appointment_date' => '2023-10-02',
        'start_time' => '12:00',
        'end_time' => '13:00',
        'total_price' => 200,
        'status' => 'Completed',
        'notes' => 'Test appointment 2',
    ]);

    // Call the API with query params (match only John's appointment)
    $response = getJson('/api/appointments?search=John&date=2023-10-01&status=Confirmed&sort_by=appointment_date&sort_order=asc&per_page=1');

    // Assert structure and content
    $response->assertOk()
        ->assertJson([
            'success' => true,
            'message' => 'Appointment fetched successfully.',
        ])
        ->assertJsonPath('appointments.current_page', 1)
        ->assertJsonPath('appointments.total', 1)
        ->assertJsonPath('appointments.data.0.customer.name', 'John Doe')
        ->assertJsonPath('appointments.data.0.appointment_date', '2023-10-01')
        ->assertJsonPath('appointments.data.0.status', 'Confirmed');
});
