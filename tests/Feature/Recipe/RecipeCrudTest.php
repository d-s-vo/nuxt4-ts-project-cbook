<?php

declare(strict_types=1);

use App\Http\Requests\StoreRecipeRequest;
use App\Models\Ingredient;
use App\Models\Recipe;
use App\Models\User;

/**
 * @param  array<string, mixed>  $overrides
 * @return array<string, mixed>
 */
function validRecipePayload(array $overrides = []): array
{
    return array_merge([
        'title' => 'Pumpkin Soup',
        'description' => 'Creamy soup for a cold evening.',
        'cooking_time' => 45,
        'servings' => 4,
        'difficulty' => 'medium',
        'steps' => ['Chop the pumpkin', 'Simmer for 30 minutes', 'Blend until smooth'],
        'ingredients' => [
            ['name' => 'Pumpkin', 'quantity' => 500, 'unit' => 'g'],
            ['name' => 'Cream', 'quantity' => 200, 'unit' => 'ml'],
        ],
    ], $overrides);
}

test('authenticated user can create a recipe with ingredients', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->post('/recipes', validRecipePayload());

    $response->assertSessionHasNoErrors();

    $this->assertDatabaseHas('recipes', [
        'user_id' => $user->id,
        'title' => 'Pumpkin Soup',
    ]);

    $recipe = Recipe::query()->where('title', 'Pumpkin Soup')->firstOrFail();
    $response->assertRedirect(route('recipes.show', $recipe->id));
    $this->assertDatabaseHas('ingredients', ['recipe_id' => $recipe->id, 'name' => 'Pumpkin']);
    $this->assertDatabaseHas('ingredients', ['recipe_id' => $recipe->id, 'name' => 'Cream']);
});

test('owner can update their recipe and ingredients are synchronized', function () {
    $owner = User::factory()->create();
    $recipe = Recipe::factory()->create(['user_id' => $owner->id]);
    Ingredient::factory()->count(2)->create(['recipe_id' => $recipe->id]);

    $response = $this
        ->actingAs($owner)
        ->put("/recipes/{$recipe->id}", validRecipePayload([
            'title' => 'Updated Soup',
            'ingredients' => [
                ['name' => 'Carrot', 'quantity' => 3, 'unit' => 'pcs'],
            ],
        ]));

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('recipes.show', $recipe->id));

    $this->assertDatabaseHas('recipes', ['id' => $recipe->id, 'title' => 'Updated Soup']);
    $this->assertDatabaseHas('ingredients', ['recipe_id' => $recipe->id, 'name' => 'Carrot']);
    $this->assertSame(1, Ingredient::query()->where('recipe_id', $recipe->id)->count());
});

test('owner can delete their recipe together with ingredients', function () {
    $owner = User::factory()->create();
    $recipe = Recipe::factory()->create(['user_id' => $owner->id]);
    Ingredient::factory()->count(2)->create(['recipe_id' => $recipe->id]);

    $response = $this
        ->actingAs($owner)
        ->delete("/recipes/{$recipe->id}");

    $response->assertRedirect(route('recipes.index'));

    $this->assertDatabaseMissing('recipes', ['id' => $recipe->id]);
    $this->assertDatabaseMissing('ingredients', ['recipe_id' => $recipe->id]);
});

test('user cannot update a recipe they do not own', function () {
    $owner = User::factory()->create();
    $stranger = User::factory()->create();
    $recipe = Recipe::factory()->create(['user_id' => $owner->id, 'title' => 'Original Title']);
    Ingredient::factory()->create(['recipe_id' => $recipe->id, 'name' => 'Original Ingredient']);

    $response = $this
        ->actingAs($stranger)
        ->put("/recipes/{$recipe->id}", validRecipePayload([
            'title' => 'Hijacked',
            'ingredients' => [
                ['name' => 'Hijacked Ingredient', 'quantity' => 1, 'unit' => 'g'],
            ],
        ]));

    $response->assertForbidden();

    // Отказ происходит до Task — не меняется ни сам рецепт, ни его ингредиенты.
    $this->assertDatabaseHas('recipes', ['id' => $recipe->id, 'title' => 'Original Title']);
    $this->assertDatabaseHas('ingredients', ['recipe_id' => $recipe->id, 'name' => 'Original Ingredient']);
    $this->assertDatabaseMissing('ingredients', ['recipe_id' => $recipe->id, 'name' => 'Hijacked Ingredient']);
});

test('user cannot delete a recipe they do not own', function () {
    $owner = User::factory()->create();
    $stranger = User::factory()->create();
    $recipe = Recipe::factory()->create(['user_id' => $owner->id]);

    $response = $this
        ->actingAs($stranger)
        ->delete("/recipes/{$recipe->id}");

    $response->assertForbidden();

    $this->assertDatabaseHas('recipes', ['id' => $recipe->id]);
});

test('recipe owner cannot be spoofed through the payload', function () {
    $user = User::factory()->create();
    $other = User::factory()->create();

    // Первый слой защиты: user_id не описан правилами, а validated() отдаёт
    // только те ключи, для которых правила есть — значит владелец из ввода не пройдёт.
    expect((new StoreRecipeRequest())->rules())->not->toHaveKey('user_id');

    // Второй слой защиты: даже просочившийся user_id не входит в $fillable модели.
    expect((new Recipe())->getFillable())->not->toContain('user_id');

    $response = $this
        ->actingAs($user)
        ->post('/recipes', validRecipePayload(['user_id' => $other->id]));

    $recipe = Recipe::query()->where('title', 'Pumpkin Soup')->firstOrFail();
    $response->assertRedirect(route('recipes.show', $recipe->id));

    $this->assertDatabaseHas('recipes', ['title' => 'Pumpkin Soup', 'user_id' => $user->id]);
    $this->assertDatabaseMissing('recipes', ['title' => 'Pumpkin Soup', 'user_id' => $other->id]);
});

test('updating a missing recipe returns 404', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->put('/recipes/999999', validRecipePayload());

    $response->assertNotFound();
});

test('deleting a missing recipe returns 404', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->delete('/recipes/999999');

    $response->assertNotFound();
});

test('guest is redirected to login', function () {
    $response = $this->post('/recipes', validRecipePayload());

    $response->assertRedirect('/login');

    $this->assertDatabaseMissing('recipes', ['title' => 'Pumpkin Soup']);
});

test('unverified user is redirected to the verification notice', function () {
    $user = User::factory()->unverified()->create();

    $response = $this
        ->actingAs($user)
        ->post('/recipes', validRecipePayload());

    $response->assertRedirect(route('verification.notice'));

    $this->assertDatabaseMissing('recipes', ['title' => 'Pumpkin Soup']);
});

test('invalid payload is rejected and nothing is stored', function (array $overrides, string $errorField) {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->from('/dashboard')
        ->post('/recipes', validRecipePayload($overrides));

    $response
        ->assertRedirect('/dashboard')
        ->assertSessionHasErrors($errorField);

    $this->assertSame(0, Recipe::query()->count());
})->with([
    'empty title' => [['title' => ''], 'title'],
    'unknown difficulty' => [['difficulty' => 'extreme'], 'difficulty'],
    'steps not an array' => [['steps' => 'just one step'], 'steps'],
    'ingredient without a name' => [
        ['ingredients' => [['quantity' => 1, 'unit' => 'g']]],
        'ingredients.0.name',
    ],
]);
