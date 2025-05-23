<?php

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use function Pest\Laravel\actingAs;

uses(RefreshDatabase::class);

it('updates a category', function () {
    $user = User::factory()->create();
    $category = Category::factory()->create();

    $payload = [
        'name' => 'Updated Category Name',
        'description' => 'Updated description for the category.',
    ];

    $response = actingAs($user, 'sanctum')
        ->putJson("/api/categories/{$category->id}", $payload);

    $response->assertOk()
        ->assertJson([
            'success' => true,
            'message' => 'Category updated successfully.',
        ]);

    expect(Category::find($category->id)->name)->toBe('Updated Category Name');
});
