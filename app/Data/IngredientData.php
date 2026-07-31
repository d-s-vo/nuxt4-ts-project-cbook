<?php

declare(strict_types=1);

namespace App\Data;

use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
final class IngredientData extends Data
{
    public function __construct(
        public int $id,
        public string $name,
        public float $quantity,
        public string $unit,
    ) {
    }
}
