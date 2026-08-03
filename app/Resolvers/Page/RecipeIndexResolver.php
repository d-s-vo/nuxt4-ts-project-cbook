<?php

declare(strict_types=1);

namespace App\Resolvers\Page;

use App\Data\RecipeData;
use App\Data\Repositories\RecipeRepository;
use Spatie\LaravelData\PaginatedDataCollection;

final class RecipeIndexResolver
{
    public function __construct(private readonly RecipeRepository $recipes)
    {
    }

    /**
     * Общий каталог: страница получает пагинированный список всех рецептов.
     *
     * @return array{recipes: PaginatedDataCollection<int, RecipeData>}
     */
    public function run(int $perPage = 12): array
    {
        return [
            'recipes' => $this->recipes->paginate($perPage),
        ];
    }
}
