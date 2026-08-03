<?php

declare(strict_types=1);

namespace App\Resolvers\Page;

use App\Data\RecipeData;

final class RecipeEditResolver
{
    /**
     * Форма редактирования получает предзаполненный рецепт в виде DTO.
     *
     * @return array{recipe: RecipeData}
     */
    public function run(RecipeData $recipe): array
    {
        return [
            'recipe' => $recipe,
        ];
    }
}
