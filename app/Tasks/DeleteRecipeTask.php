<?php

declare(strict_types=1);

namespace App\Tasks;

use App\Data\Repositories\RecipeRepository;

final class DeleteRecipeTask extends BaseTask
{
    public function __construct(private readonly RecipeRepository $recipes)
    {
    }

    public function run(int $id): void
    {
        $this->recipes->delete($id);
    }
}
