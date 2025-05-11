<?php

use App\Models\User;
use App\Models\Customer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use function Pest\Laravel\actingAs;

uses(RefreshDatabase::class);

it('updates a customer', function () {
    $user = User::factory()->create();
    $customer = Customer::factory()->create();

    $payload = [
        'name' => 'Updated Name',
        'email' => 'updated@example.com',
        'address' => 'Updated Address',
        'contact_number' => '09268895743',
    ];

    $response = actingAs($user, 'sanctum')
        ->putJson("/api/customers/{$customer->id}", $payload);

    $response->assertOk()
        ->assertJson([
            'success' => true,
            'message' => 'Customer updated successfully',
        ]);

    expect(Customer::find($customer->id)->name)->toBe('Updated Name');
});