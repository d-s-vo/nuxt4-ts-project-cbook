<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Recipe;
use App\Models\User;

/**
 * Каталог рецептов общий: просмотр открыт всем аутентифицированным (гейт — middleware
 * auth+verified на маршрутах). Мутации — только владелец рецепта.
 */
final class RecipePolicy
{
    /**
     * Администратор управляет всеми рецептами через админ-панель — осознанный супер-доступ
     * поверх проверки владельца. Для обычных пользователей возвращаем null: решение остаётся
     * за конкретными методами (owner-scoping не ослабляется).
     */
    public function before(User $user): ?bool
    {
        return $user->is_admin ? true : null;
    }

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Recipe $recipe): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Recipe $recipe): bool
    {
        return $user->id === $recipe->user_id;
    }

    public function delete(User $user, Recipe $recipe): bool
    {
        return $user->id === $recipe->user_id;
    }
}
