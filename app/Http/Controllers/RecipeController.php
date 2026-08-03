<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Data\RecipeData;
use App\Data\Repositories\RecipeRepository;
use App\Http\Requests\StoreRecipeRequest;
use App\Http\Requests\UpdateRecipeRequest;
use App\Resolvers\Page\RecipeEditResolver;
use App\Resolvers\Page\RecipeIndexResolver;
use App\Resolvers\Page\RecipeShowResolver;
use App\Tasks\CreateRecipeTask;
use App\Tasks\DeleteRecipeTask;
use App\Tasks\UpdateRecipeTask;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

class RecipeController extends Controller
{
    public function __construct(
        private RecipeRepository $recipes,
        private CreateRecipeTask $createRecipe,
        private UpdateRecipeTask $updateRecipe,
        private DeleteRecipeTask $deleteRecipe,
        private RecipeIndexResolver $recipeIndex,
        private RecipeShowResolver $recipeShow,
        private RecipeEditResolver $recipeEdit,
    ) {
    }

    /**
     * Общий каталог: список всех рецептов с пагинацией доступен любому
     * аутентифицированному пользователю.
     */
    public function index(): Response
    {
        return Inertia::render('Recipe/Index', $this->recipeIndex->run());
    }

    public function create(): Response
    {
        return Inertia::render('Recipe/Create');
    }

    /**
     * Просмотр рецепта открыт всем; флаг canUpdate управляет показом кнопок
     * редактирования и удаления — их видит только владелец.
     */
    public function show(Request $request, int $recipe): Response
    {
        $found = $this->recipes->findModel($recipe);
        abort_if($found === null, 404);

        return Inertia::render('Recipe/Show', $this->recipeShow->run(
            RecipeData::from($found),
            Gate::allows('update', $found),
        ));
    }

    /**
     * Форма редактирования — только для владельца; чужой рецепт → 403.
     */
    public function edit(int $recipe): Response
    {
        $found = $this->recipes->findModel($recipe);
        abort_if($found === null, 404);

        Gate::authorize('update', $found);

        return Inertia::render('Recipe/Edit', $this->recipeEdit->run(RecipeData::from($found)));
    }

    /**
     * Создать рецепт может любой аутентифицированный пользователь (маршрут под
     * auth+verified) — владельцем становится текущий пользователь.
     */
    public function store(StoreRecipeRequest $request): RedirectResponse
    {
        $user = $request->user();
        assert($user !== null);

        $id = $this->createRecipe->run($user->id, $request->validated());

        return Redirect::route('recipes.show', $id);
    }

    public function update(UpdateRecipeRequest $request, int $recipe): RedirectResponse
    {
        $found = $this->recipes->findModel($recipe);
        abort_if($found === null, 404);

        Gate::authorize('update', $found);

        $this->updateRecipe->run($recipe, $request->validated());

        return Redirect::route('recipes.show', $recipe);
    }

    public function destroy(Request $request, int $recipe): RedirectResponse
    {
        $found = $this->recipes->findModel($recipe);
        abort_if($found === null, 404);

        Gate::authorize('delete', $found);

        $this->deleteRecipe->run($recipe);

        return Redirect::route('recipes.index');
    }
}
