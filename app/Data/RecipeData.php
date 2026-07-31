<?php

declare(strict_types=1);

namespace App\Data;

use App\Enums\Difficulty;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
final class RecipeData extends Data
{
    /**
     * @param  array<int, string>  $steps
     * @param  array<int, IngredientData>  $ingredients
     */
    public function __construct(
        public int $id,
        public string $title,
        public string $description,
        public int $cooking_time,
        public int $servings,
        public Difficulty $difficulty,
        public array $steps,
        #[DataCollectionOf(IngredientData::class)]
        public array $ingredients,
        public ?string $created_at,
        public ?string $updated_at,
    ) {
    }
}
