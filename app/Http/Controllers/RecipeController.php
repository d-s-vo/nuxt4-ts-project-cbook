<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Data\Repositories\RecipeRepository;
use App\Http\Requests\StoreRecipeRequest;
use App\Http\Requests\UpdateRecipeRequest;
use App\Tasks\CreateRecipeTask;
use App\Tasks\DeleteRecipeTask;
use App\Tasks\UpdateRecipeTask;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Redirect;

class RecipeController extends Controller
{
    public function __construct(
        private RecipeRepository $recipes,
        private CreateRecipeTask $createRecipe,
        private UpdateRecipeTask $updateRecipe,
        private DeleteRecipeTask $deleteRecipe,
    ) {
    }

    /**
     * Создать рецепт может любой аутентифицированный пользователь (маршрут под
     * auth+verified) — владельцем становится текущий пользователь.
     */
    public function store(StoreRecipeRequest $request): RedirectResponse
    {
        $user = $request->user();
        assert($user !== null);

        $this->createRecipe->run($user->id, $request->validated());

        // Страниц рецептов пока нет — после мутаций возвращаемся на дашборд.
        return Redirect::route('dashboard');
    }

    public function update(UpdateRecipeRequest $request, int $recipe): RedirectResponse
    {
        $found = $this->recipes->findModel($recipe);
        abort_if($found === null, 404);

        Gate::authorize('update', $found);

        $this->updateRecipe->run($recipe, $request->validated());

        return Redirect::route('dashboard');
    }

    public function destroy(Request $request, int $recipe): RedirectResponse
    {
        $found = $this->recipes->findModel($recipe);
        abort_if($found === null, 404);

        Gate::authorize('delete', $found);

        $this->deleteRecipe->run($recipe);

        return Redirect::route('dashboard');
    }
}
