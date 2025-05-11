<?php

use App\Models\User;
use App\Models\Customer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use function Pest\Laravel\actingAs;

uses(RefreshDatabase::class);

it('deletes a customer', function () {
    $user = User::factory()->create();
    $customer = Customer::factory()->create();

    $response = actingAs($user, 'sanctum')
        ->deleteJson("/api/customers/{$customer->id}");

    $response->assertOk()
        ->assertJson([
            'status' => true,
            'message' => 'Customer deleted successfully.'
        ]);

    expect(Customer::find($customer->id))->toBeNull();
});
