<?php

declare(strict_types=1);

namespace App\Filament\Resources\Recipes\Pages;

use App\Data\Repositories\RecipeRepository;
use App\Filament\Resources\Recipes\RecipeResource;
use App\Models\Recipe;
use App\Tasks\DeleteRecipeTask;
use App\Tasks\UpdateRecipeTask;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditRecipe extends EditRecord
{
    protected static string $resource = RecipeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()
                ->using(function (Recipe $record): void {
                    app(DeleteRecipeTask::class)->run($record->id);
                }),
        ];
    }

    /**
     * Ингредиенты — связь, а не колонка: подгружаем их в форму отдельно.
     *
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function mutateFormDataBeforeFill(array $data): array
    {
        $recipe = $this->getRecord();
        assert($recipe instanceof Recipe);

        $data['ingredients'] = app(RecipeRepository::class)->ingredientsAsFormData($recipe->id);

        return $data;
    }

    /**
     * Обновление идёт через доменный Task — рецепт и ингредиенты синхронизируются транзакцией.
     *
     * @param  array<string, mixed>  $data
     */
    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        assert($record instanceof Recipe);
        unset($data['user_id']);

        app(UpdateRecipeTask::class)->run($record->id, $data);

        $recipe = app(RecipeRepository::class)->findModel($record->id);
        assert($recipe !== null);

        return $recipe;
    }
}
