<?php

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\deleteJson;

uses(RefreshDatabase::class);

it('deletes a category', function () {
    // Simulate authenticated user
    $user = User::factory()->create();
    $category = Category::factory()->create();

    $response = actingAs($user, 'sanctum')
        ->deleteJson("/api/categories/{$category->id}");

    $response->assertOk()
        ->assertJson([
            'success' => true,
            'message' => 'Category deleted successfully.',
        ]);

    expect(\App\Models\Category::find($category->id))->toBeNull();
});
