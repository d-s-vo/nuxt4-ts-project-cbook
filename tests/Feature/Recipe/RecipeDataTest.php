<?php

declare(strict_types=1);

use App\Data\IngredientData;
use App\Data\RecipeData;
use App\Enums\Difficulty;
use App\Models\Ingredient;
use App\Models\Recipe;

it('builds from a recipe model with its ingredients', function () {
    $recipe = Recipe::factory()
        ->has(Ingredient::factory()->count(2))
        ->create([
            'difficulty' => Difficulty::Medium,
            'steps' => ['Chop', 'Cook', 'Serve'],
        ]);

    $data = RecipeData::from($recipe->load('ingredients'));

    expect($data->id)->toBe($recipe->id)
        ->and($data->difficulty)->toBe(Difficulty::Medium)
        ->and($data->steps)->toBe(['Chop', 'Cook', 'Serve'])
        ->and($data->ingredients)->toHaveCount(2)
        ->and($data->ingredients)->each->toBeInstanceOf(IngredientData::class);
});

it('never leaks the owner id', function () {
    $recipe = Recipe::factory()
        ->has(Ingredient::factory()->count(2))
        ->create();

    $array = RecipeData::from($recipe->load('ingredients'))->toArray();

    expect($array)->toHaveKeys([
        'id', 'title', 'description', 'cooking_time', 'servings',
        'difficulty', 'steps', 'ingredients', 'created_at', 'updated_at',
    ])->and($array)->not->toHaveKey('user_id');
});
