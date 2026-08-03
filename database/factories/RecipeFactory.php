<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\Difficulty;
use App\Models\Recipe;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Recipe>
 */
class RecipeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'title' => fake()->sentence(3),
            'description' => fake()->paragraph(),
            'cooking_time' => fake()->numberBetween(5, 240),
            'servings' => fake()->numberBetween(1, 8),
            'difficulty' => fake()->randomElement(Difficulty::cases()),
            'steps' => fake()->sentences(fake()->numberBetween(2, 5)),
        ];
    }
}
