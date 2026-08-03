<?php

declare(strict_types=1);

namespace App\Filament\Resources\Recipes\Pages;

use App\Data\Repositories\RecipeRepository;
use App\Filament\Resources\Recipes\RecipeResource;
use App\Tasks\CreateRecipeTask;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateRecipe extends CreateRecord
{
    protected static string $resource = RecipeResource::class;

    /**
     * Создание идёт через доменный Task — рецепт и его ингредиенты пишутся одной транзакцией.
     *
     * @param  array<string, mixed>  $data
     */
    protected function handleRecordCreation(array $data): Model
    {
        $authorId = $data['user_id'];
        assert(is_int($authorId) || is_string($authorId));
        unset($data['user_id']);

        $recipeId = app(CreateRecipeTask::class)->run((int) $authorId, $data);

        $recipe = app(RecipeRepository::class)->findModel($recipeId);
        assert($recipe !== null);

        return $recipe;
    }
}
