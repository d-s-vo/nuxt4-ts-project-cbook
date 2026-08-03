<?php

declare(strict_types=1);

namespace App\Data\Repositories;

use App\Data\RecipeData;
use App\Models\Ingredient;
use App\Models\Recipe;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Spatie\LaravelData\PaginatedDataCollection;

final class RecipeRepository extends BaseRepository
{
    /**
     * @return PaginatedDataCollection<int, RecipeData>
     */
    public function paginate(int $perPage = 12): PaginatedDataCollection
    {
        $recipes = Recipe::query()
            ->with('ingredients')
            ->latest()
            ->paginate($perPage);

        return RecipeData::collect($recipes, PaginatedDataCollection::class);
    }

    public function findModel(int $id): ?Recipe
    {
        return Recipe::query()
            ->with('ingredients')
            ->find($id);
    }

    /**
     * @param  array<string, mixed>  $recipeAttributes
     * @param  array<int, array<string, mixed>>  $ingredients
     */
    public function createForUser(int $userId, array $recipeAttributes, array $ingredients): int
    {
        return DB::transaction(function () use ($userId, $recipeAttributes, $ingredients): int {
            $user = User::query()->findOrFail($userId);

            $recipe = $user->recipes()->create($recipeAttributes);
            $recipe->ingredients()->createMany($ingredients);

            return $recipe->id;
        });
    }

    /**
     * @param  array<string, mixed>  $recipeAttributes
     * @param  array<int, array<string, mixed>>  $ingredients
     */
    public function update(int $id, array $recipeAttributes, array $ingredients): void
    {
        DB::transaction(function () use ($id, $recipeAttributes, $ingredients): void {
            $recipe = Recipe::query()->findOrFail($id);
            $recipe->fill($recipeAttributes);
            $recipe->save();

            $recipe->ingredients()->delete();
            $recipe->ingredients()->createMany($ingredients);
        });
    }

    public function delete(int $id): void
    {
        Recipe::query()->findOrFail($id)->delete();
    }

    /**
     * Ингредиенты рецепта в форме, пригодной для заполнения формы админ-панели.
     *
     * @return array<int, array{name: string, quantity: float, unit: string}>
     */
    public function ingredientsAsFormData(int $recipeId): array
    {
        $recipe = Recipe::query()->with('ingredients')->findOrFail($recipeId);

        return $recipe->ingredients
            ->map(fn (Ingredient $ingredient): array => [
                'name' => $ingredient->name,
                'quantity' => $ingredient->quantity,
                'unit' => $ingredient->unit,
            ])
            ->all();
    }
}
