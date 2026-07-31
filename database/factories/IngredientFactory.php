<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Ingredient;
use App\Models\Recipe;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Ingredient>
 */
class IngredientFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'recipe_id' => Recipe::factory(),
            'name' => fake()->word(),
            'quantity' => fake()->randomFloat(2, 0.25, 500),
            'unit' => fake()->randomElement(['g', 'kg', 'ml', 'l', 'pcs', 'tbsp', 'tsp']),
        ];
    }
}
