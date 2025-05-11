<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\postJson;
use function Pest\Laravel\assertDatabaseHas;

uses(RefreshDatabase::class);

it('creates a customer', function () {
    // Simulate authenticated user
    $user = User::factory()->create();
    actingAs($user, 'sanctum');

    // Prepare test data
    $customerData = [
        'name' => 'Test Customer',
        'email' => 'test@example.com',
        'address' => '123 Test Street',
        'contact_number' => '09123456987',
    ];

    // Send POST request to the endpoint
    $response = postJson('/api/customers', $customerData);

    $response->assertCreated()
        ->assertJson([
            'success' => true,
            'message' => 'Customer created successfully',
        ]);

    assertDatabaseHas('customers', [
        'email' => 'test@example.com',
    ]);
});
