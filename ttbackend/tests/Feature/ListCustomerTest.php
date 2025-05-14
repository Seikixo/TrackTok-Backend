<?php

use App\Models\User;
use App\Models\Customer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\getJson;

uses(RefreshDatabase::class);

it('fetches a list of customers with pagination, sorting, and searching', function () {
    // Simulate authenticated user
    $user = User::factory()->create();
    actingAs($user, 'sanctum');

    // Create customers
    Customer::factory()->create([
        'name' => 'John Doe',
        'email' => 'john@example.com',
    ]);
    Customer::factory()->create([
        'name' => 'Jane Smith',
        'email' => 'jane@example.com',
    ]);


    $response = getJson('/api/customers?search=Jane&sort_by=name&sort_order=asc&per_page=1');

    // Assert response is successful
    $response->assertOk()
        ->assertJson([
            'success' => true,
        ]);


    $response->assertJsonFragment([
        'name' => 'Jane Smith',
        'email' => 'jane@example.com',
    ]);

    // Check pagination, sorting, and total count
    $response->assertJsonStructure([
        'success',
        'customers' => [
            'current_page',
            'data',
            'first_page_url',
            'from',
            'last_page',
            'last_page_url',
            'links',
            'next_page_url',
            'path',
            'per_page',
            'prev_page_url',
            'to',
            'total',
        ],
    ]);

    $response->assertJsonFragment([
        'current_page' => 1,
        'per_page' => 1,
        'total' => 1
    ]);
});
