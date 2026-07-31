<?php

declare(strict_types=1);

namespace App\Tasks;

use App\Data\Repositories\RecipeRepository;

final class CreateRecipeTask extends BaseTask
{
    public function __construct(private readonly RecipeRepository $recipes)
    {
    }

    /**
     * @param  array<string, mixed>  $data  провалидированный payload формы (атрибуты рецепта + ingredients)
     */
    public function run(int $userId, array $data): int
    {
        /** @var array<int, array<string, mixed>> $ingredients */
        $ingredients = $data['ingredients'] ?? [];
        unset($data['ingredients']);

        return $this->recipes->createForUser($userId, $data, $ingredients);
    }
}
