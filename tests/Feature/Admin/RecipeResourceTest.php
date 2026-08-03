<?php

declare(strict_types=1);

use App\Filament\Resources\Recipes\Pages\CreateRecipe;
use App\Filament\Resources\Recipes\Pages\EditRecipe;
use App\Filament\Resources\Recipes\Pages\ListRecipes;
use App\Models\Ingredient;
use App\Models\Recipe;
use App\Models\User;
use Filament\Facades\Filament;
use Livewire\Livewire;

beforeEach(function () {
    Filament::setCurrentPanel('admin');
    $this->actingAs(User::factory()->admin()->create());
});

test('the recipe list shows recipes from every owner', function () {
    $first = Recipe::factory()->create();
    $second = Recipe::factory()->create();

    Livewire::test(ListRecipes::class)
        ->assertCanSeeTableRecords([$first, $second]);
});

test('creating a recipe through the panel persists it with its ingredients', function () {
    $author = User::factory()->create();

    Livewire::test(CreateRecipe::class)
        ->fillForm([
            'user_id' => $author->id,
            'title' => 'Panel Soup',
            'description' => 'Cooked from the admin panel.',
            'cooking_time' => 30,
            'servings' => 2,
            'difficulty' => 'medium',
            'steps' => [['step' => 'Boil water'], ['step' => 'Add everything']],
            'ingredients' => [
                ['name' => 'Salt', 'quantity' => 1, 'unit' => 'g'],
                ['name' => 'Water', 'quantity' => 500, 'unit' => 'ml'],
            ],
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $recipe = Recipe::query()->where('title', 'Panel Soup')->firstOrFail();

    expect($recipe->user_id)->toBe($author->id);
    expect($recipe->ingredients()->count())->toBe(2);
    $this->assertDatabaseHas('ingredients', ['recipe_id' => $recipe->id, 'name' => 'Salt']);
    $this->assertDatabaseHas('ingredients', ['recipe_id' => $recipe->id, 'name' => 'Water']);
});

test('editing a recipe through the panel synchronizes its ingredients', function () {
    $recipe = Recipe::factory()->create(['title' => 'Before']);
    Ingredient::factory()->count(2)->create(['recipe_id' => $recipe->id]);

    Livewire::test(EditRecipe::class, ['record' => $recipe->getKey()])
        ->fillForm([
            'title' => 'After',
            'ingredients' => [
                ['name' => 'Only ingredient', 'quantity' => 3, 'unit' => 'pcs'],
            ],
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas('recipes', ['id' => $recipe->id, 'title' => 'After']);
    expect($recipe->fresh()?->ingredients()->count())->toBe(1);
    $this->assertDatabaseHas('ingredients', ['recipe_id' => $recipe->id, 'name' => 'Only ingredient']);
});

test('deleting a recipe through the panel cascades to its ingredients', function () {
    $recipe = Recipe::factory()->create();
    Ingredient::factory()->count(2)->create(['recipe_id' => $recipe->id]);

    Livewire::test(EditRecipe::class, ['record' => $recipe->getKey()])
        ->callAction('delete');

    $this->assertDatabaseMissing('recipes', ['id' => $recipe->id]);
    $this->assertDatabaseMissing('ingredients', ['recipe_id' => $recipe->id]);
});
