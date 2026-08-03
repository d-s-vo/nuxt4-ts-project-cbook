<?php

declare(strict_types=1);

namespace App\Resolvers\Page;

use App\Data\RecipeData;

final class RecipeShowResolver
{
    /**
     * Просмотр открыт всем; флаг владения вычислен на сервере, чтобы страница
     * знала, показывать ли действия редактирования и удаления.
     *
     * @return array{recipe: RecipeData, canUpdate: bool}
     */
    public function run(RecipeData $recipe, bool $canUpdate): array
    {
        return [
            'recipe' => $recipe,
            'canUpdate' => $canUpdate,
        ];
    }
}
