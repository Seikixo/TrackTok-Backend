<?php

use App\Models\Appointment;
use App\Models\User;
use App\Models\Customer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use function Pest\Laravel\actingAs;

uses(RefreshDatabase::class);

it('deletes an appointment', function () {
    $user = User::factory()->create();
    $customer = Customer::factory()->create();
    $appointment = Appointment::factory()->create(['customer_id' => $customer->id]);

    $response = actingAs($user, 'sanctum')
        ->deleteJson("/api/appointments/{$appointment->id}");

    $response->assertOk()
        ->assertJson([
            'status' => true,
            'message' => 'Appointment deleted successfully.'
        ]);

    expect(Appointment::find($appointment->id))->toBeNull();
});