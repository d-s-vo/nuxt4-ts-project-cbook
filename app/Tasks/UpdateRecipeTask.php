<?php

declare(strict_types=1);

namespace App\Tasks;

use App\Data\Repositories\RecipeRepository;

final class UpdateRecipeTask extends BaseTask
{
    public function __construct(private readonly RecipeRepository $recipes)
    {
    }

    /**
     * @param  array<string, mixed>  $data  провалидированный payload формы (атрибуты рецепта + ingredients)
     */
    public function run(int $id, array $data): void
    {
        /** @var array<int, array<string, mixed>> $ingredients */
        $ingredients = $data['ingredients'] ?? [];
        unset($data['ingredients']);

        $this->recipes->update($id, $data, $ingredients);
    }
}
