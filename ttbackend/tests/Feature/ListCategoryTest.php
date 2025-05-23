<?php

use App\Models\User;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\getJson;

uses(RefreshDatabase::class);

it('fetches a list of category with pagination, sorting, and searching', function () {
    // Simulate authenticated user
    $user = User::factory()->create();
    actingAs($user, 'sanctum');

    // Create categories
    Category::factory()->create([
        'name' => 'Category A',
        'description' => 'Description for Category A',
    ]);
    Category::factory()->create([
        'name' => 'Category B',
        'description' => 'Description for Category B',
    ]);

    $response = getJson('/api/categories?search=Category&sort_by=name&sort_order=asc&per_page=1');

    // Assert response is successful

    $response->assertOk()
        ->assertJson([
            'success' => true,
            'message' => 'Categories fetched successfully.',
        ])
        ->assertJsonPath('categories.current_page', 1)
        ->assertJsonPath('categories.total', 2)
        ->assertJsonPath('categories.data.0.name', 'Category A')
        ->assertJsonPath('categories.data.0.description', 'Description for Category A');
});
