<?php

declare(strict_types=1);

use App\Data\IngredientData;
use App\Data\RecipeData;
use App\Data\Repositories\RecipeRepository;
use App\Enums\Difficulty;
use App\Models\Ingredient;
use App\Models\Recipe;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Spatie\LaravelData\PaginatedDataCollection;

beforeEach(function () {
    $this->recipes = app(RecipeRepository::class);
});

function recipeAttributes(): array
{
    return [
        'title' => 'Pancakes',
        'description' => 'Fluffy breakfast pancakes.',
        'cooking_time' => 20,
        'servings' => 4,
        'difficulty' => Difficulty::Low,
        'steps' => ['Mix the batter', 'Fry on both sides'],
    ];
}

it('creates a recipe with its ingredients and assigns the owner', function () {
    $user = User::factory()->create();

    $id = $this->recipes->createForUser($user->id, recipeAttributes(), [
        ['name' => 'Flour', 'quantity' => 200.0, 'unit' => 'g'],
        ['name' => 'Milk', 'quantity' => 300.0, 'unit' => 'ml'],
        ['name' => 'Egg', 'quantity' => 2.0, 'unit' => 'pcs'],
    ]);

    $this->assertDatabaseHas('recipes', [
        'id' => $id,
        'user_id' => $user->id,
        'title' => 'Pancakes',
        'difficulty' => 'low',
    ]);

    expect(Ingredient::query()->where('recipe_id', $id)->count())->toBe(3);
});

it('synchronizes ingredients on update', function () {
    $recipe = Recipe::factory()
        ->has(Ingredient::factory()->count(3))
        ->create();

    expect($recipe->ingredients()->count())->toBe(3);

    $this->recipes->update($recipe->id, recipeAttributes(), [
        ['name' => 'Sugar', 'quantity' => 50.0, 'unit' => 'g'],
        ['name' => 'Salt', 'quantity' => 1.0, 'unit' => 'tsp'],
    ]);

    expect($recipe->ingredients()->count())->toBe(2)
        ->and($recipe->fresh()->title)->toBe('Pancakes');

    $this->assertDatabaseHas('ingredients', ['recipe_id' => $recipe->id, 'name' => 'Sugar']);
    $this->assertDatabaseMissing('ingredients', ['recipe_id' => $recipe->id, 'name' => 'Salt', 'quantity' => 999]);
});

it('deletes a recipe and cascades to its ingredients', function () {
    $recipe = Recipe::factory()
        ->has(Ingredient::factory()->count(3))
        ->create();

    $this->recipes->delete($recipe->id);

    $this->assertDatabaseMissing('recipes', ['id' => $recipe->id]);
    expect(Ingredient::query()->where('recipe_id', $recipe->id)->count())->toBe(0);
});

it('paginates every recipe across owners and eager loads ingredients', function () {
    $alice = User::factory()->create();
    $bob = User::factory()->create();

    $aliceRecipe = Recipe::factory()->for($alice)->has(Ingredient::factory()->count(2))->create();
    $bobRecipe = Recipe::factory()->for($bob)->has(Ingredient::factory()->count(2))->create();

    Model::preventLazyLoading();

    $page = $this->recipes->paginate();

    expect($page)->toBeInstanceOf(PaginatedDataCollection::class);

    $collection = collect($page->items()->items());
    $ids = $collection->map(fn (RecipeData $recipe) => $recipe->id)->all();

    expect($ids)->toContain($aliceRecipe->id, $bobRecipe->id)
        ->and($collection->firstOrFail()->ingredients)->each->toBeInstanceOf(IngredientData::class);
});

it('finds a recipe model with its ingredients or returns null', function () {
    $recipe = Recipe::factory()->has(Ingredient::factory()->count(2))->create();

    $found = $this->recipes->findModel($recipe->id);

    expect($found)->toBeInstanceOf(Recipe::class)
        ->and($found->id)->toBe($recipe->id)
        ->and($found->user_id)->toBe($recipe->user_id)
        ->and($found->relationLoaded('ingredients'))->toBeTrue()
        ->and($found->ingredients)->toHaveCount(2);

    expect($this->recipes->findModel(999999))->toBeNull();
});

it('cascades deletion from the owner down to recipes and ingredients', function () {
    $user = User::factory()->create();
    $recipe = Recipe::factory()->for($user)->has(Ingredient::factory()->count(2))->create();

    $user->delete();

    $this->assertDatabaseMissing('recipes', ['id' => $recipe->id]);
    expect(Ingredient::query()->where('recipe_id', $recipe->id)->count())->toBe(0);
});
