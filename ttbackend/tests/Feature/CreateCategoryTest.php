<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\postJson;
use function Pest\Laravel\assertDatabaseHas;

uses(RefreshDatabase::class);

it('creates a category', function () {
    // Simulate authenticated user
    $user = User::factory()->create();
    actingAs($user, 'sanctum');

    // Prepare test data
    $categoryData = [
        "name" => "New Category",
        "description" => "This is a new category.",
    ];

    // Send POST request to the endpoint
    $response = postJson('/api/categories/', $categoryData);

    $response->assertCreated()
        ->assertJson([
            'success' => true,
            'message' => 'Category created successfully.',
        ]);

    assertDatabaseHas('categories', [
        'name' => 'New Category',
        'description' => 'This is a new category.'
    ]);
});