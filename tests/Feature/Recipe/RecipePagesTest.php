<?php

declare(strict_types=1);

use App\Models\Ingredient;
use App\Models\Recipe;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Inertia\Testing\AssertableInertia;

/**
 * @param  array<string, mixed>  $overrides
 * @return array<string, mixed>
 */
function recipeFormPayload(array $overrides = []): array
{
    return array_merge([
        'title' => 'Roasted Vegetables',
        'description' => 'A simple tray bake.',
        'cooking_time' => 40,
        'servings' => 3,
        'difficulty' => 'low',
        'steps' => ['Chop the vegetables', 'Roast for 35 minutes'],
        'ingredients' => [
            ['name' => 'Carrot', 'quantity' => 4, 'unit' => 'pcs'],
            ['name' => 'Olive oil', 'quantity' => 30, 'unit' => 'ml'],
        ],
    ], $overrides);
}

test('index shows the shared catalog with recipes from every user', function () {
    $viewer = User::factory()->create();
    $authorA = User::factory()->create();
    $authorB = User::factory()->create();

    $recipeA = Recipe::factory()->create(['user_id' => $authorA->id, 'title' => 'Alpha Dish']);
    Ingredient::factory()->create(['recipe_id' => $recipeA->id]);
    $recipeB = Recipe::factory()->create(['user_id' => $authorB->id, 'title' => 'Beta Dish']);
    Ingredient::factory()->create(['recipe_id' => $recipeB->id]);

    $this->actingAs($viewer)
        ->get('/recipes')
        ->assertOk()
        ->assertInertia(fn (AssertableInertia $page) => $page
            ->component('Recipe/Index')
            ->has('recipes.data', 2)
            ->has('recipes.meta')
            ->has('recipes.links')
            ->where('recipes.meta.total', 2));
});

test('index does not trigger lazy loading of ingredients', function () {
    $user = User::factory()->create();
    Recipe::factory()
        ->count(3)
        ->has(Ingredient::factory()->count(2))
        ->create(['user_id' => $user->id]);

    Model::preventLazyLoading();

    $this->actingAs($user)
        ->get('/recipes')
        ->assertOk();
});

test('index paginates the catalog', function () {
    $user = User::factory()->create();
    Recipe::factory()->count(15)->create(['user_id' => $user->id]);

    $this->actingAs($user)
        ->get('/recipes')
        ->assertOk()
        ->assertInertia(fn (AssertableInertia $page) => $page
            ->component('Recipe/Index')
            ->has('recipes.data', 12)
            ->where('recipes.meta.per_page', 12)
            ->where('recipes.meta.last_page', 2)
            ->where('recipes.meta.total', 15));
});

test('anyone verified can view any recipe, including a stranger recipe', function () {
    $owner = User::factory()->create();
    $stranger = User::factory()->create();
    $recipe = Recipe::factory()->create(['user_id' => $owner->id]);
    Ingredient::factory()->count(2)->create(['recipe_id' => $recipe->id]);

    $this->actingAs($stranger)
        ->get("/recipes/{$recipe->id}")
        ->assertOk()
        ->assertInertia(fn (AssertableInertia $page) => $page
            ->component('Recipe/Show')
            ->where('recipe.id', $recipe->id)
            ->has('recipe.steps')
            ->has('recipe.ingredients', 2)
            ->where('canUpdate', false));
});

test('show does not trigger lazy loading of ingredients', function () {
    $user = User::factory()->create();
    $recipe = Recipe::factory()->create(['user_id' => $user->id]);
    Ingredient::factory()->count(3)->create(['recipe_id' => $recipe->id]);

    Model::preventLazyLoading();

    $this->actingAs($user)
        ->get("/recipes/{$recipe->id}")
        ->assertOk();
});

test('canUpdate is true for the owner viewing their own recipe', function () {
    $owner = User::factory()->create();
    $recipe = Recipe::factory()->create(['user_id' => $owner->id]);

    $this->actingAs($owner)
        ->get("/recipes/{$recipe->id}")
        ->assertOk()
        ->assertInertia(fn (AssertableInertia $page) => $page
            ->component('Recipe/Show')
            ->where('canUpdate', true));
});

test('showing a missing recipe returns 404', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get('/recipes/999999')
        ->assertNotFound();
});

test('the create page renders an empty form', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get('/recipes/create')
        ->assertOk()
        ->assertInertia(fn (AssertableInertia $page) => $page->component('Recipe/Create'));
});

test('the owner sees the prefilled edit form', function () {
    $owner = User::factory()->create();
    $recipe = Recipe::factory()->create(['user_id' => $owner->id, 'title' => 'Editable Dish']);
    Ingredient::factory()->count(2)->create(['recipe_id' => $recipe->id]);

    $this->actingAs($owner)
        ->get("/recipes/{$recipe->id}/edit")
        ->assertOk()
        ->assertInertia(fn (AssertableInertia $page) => $page
            ->component('Recipe/Edit')
            ->where('recipe.id', $recipe->id)
            ->where('recipe.title', 'Editable Dish')
            ->has('recipe.ingredients', 2));
});

test('a stranger cannot open the edit form of a recipe they do not own', function () {
    $owner = User::factory()->create();
    $stranger = User::factory()->create();
    $recipe = Recipe::factory()->create(['user_id' => $owner->id]);

    $this->actingAs($stranger)
        ->get("/recipes/{$recipe->id}/edit")
        ->assertForbidden();
});

test('editing a missing recipe returns 404', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get('/recipes/999999/edit')
        ->assertNotFound();
});

test('guests are redirected to login on every recipe page', function () {
    $recipe = Recipe::factory()->create();

    $this->get('/recipes')->assertRedirect('/login');
    $this->get('/recipes/create')->assertRedirect('/login');
    $this->get("/recipes/{$recipe->id}")->assertRedirect('/login');
    $this->get("/recipes/{$recipe->id}/edit")->assertRedirect('/login');
});

test('unverified users are redirected to the verification notice on every recipe page', function () {
    $user = User::factory()->unverified()->create();
    $recipe = Recipe::factory()->create(['user_id' => $user->id]);

    $notice = route('verification.notice');

    $this->actingAs($user)->get('/recipes')->assertRedirect($notice);
    $this->actingAs($user)->get('/recipes/create')->assertRedirect($notice);
    $this->actingAs($user)->get("/recipes/{$recipe->id}")->assertRedirect($notice);
    $this->actingAs($user)->get("/recipes/{$recipe->id}/edit")->assertRedirect($notice);
});

test('a full create flow stores the recipe and redirects to its page', function () {
    $user = User::factory()->create();

    $this->actingAs($user)->get('/recipes/create')->assertOk();

    $response = $this->actingAs($user)->post('/recipes', recipeFormPayload());

    $recipe = Recipe::query()->where('title', 'Roasted Vegetables')->firstOrFail();
    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('recipes.show', $recipe->id));
});

test('a full edit flow updates the recipe and redirects to its page', function () {
    $owner = User::factory()->create();
    $recipe = Recipe::factory()->create(['user_id' => $owner->id]);
    Ingredient::factory()->create(['recipe_id' => $recipe->id]);

    $this->actingAs($owner)->get("/recipes/{$recipe->id}/edit")->assertOk();

    $response = $this->actingAs($owner)->put(
        "/recipes/{$recipe->id}",
        recipeFormPayload(['title' => 'Renamed Dish']),
    );

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('recipes.show', $recipe->id));

    $this->assertDatabaseHas('recipes', ['id' => $recipe->id, 'title' => 'Renamed Dish']);
});

test('deleting a recipe redirects to the catalog', function () {
    $owner = User::factory()->create();
    $recipe = Recipe::factory()->create(['user_id' => $owner->id]);

    $this->actingAs($owner)
        ->delete("/recipes/{$recipe->id}")
        ->assertRedirect(route('recipes.index'));

    $this->assertDatabaseMissing('recipes', ['id' => $recipe->id]);
});
