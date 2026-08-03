<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import type { Paginated } from '@/types/pagination';

defineProps<{
    recipes: Paginated<App.Data.RecipeData>;
}>();

const difficultyLabels: Record<App.Enums.Difficulty, string> = {
    low: 'Простой',
    medium: 'Средний',
    high: 'Сложный',
};
</script>

<template>
    <Head title="Рецепты" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold leading-tight text-gray-800">
                    Рецепты
                </h2>
                <Link
                    :href="route('recipes.create')"
                    class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                >
                    Новый рецепт
                </Link>
            </div>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <p
                    v-if="recipes.data.length === 0"
                    class="rounded-lg bg-white p-8 text-center text-gray-500 shadow"
                >
                    Пока нет ни одного рецепта. Создайте первый!
                </p>

                <div
                    v-else
                    class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3"
                >
                    <Link
                        v-for="recipe in recipes.data"
                        :key="recipe.id"
                        :href="route('recipes.show', recipe.id)"
                        class="flex flex-col rounded-lg bg-white p-6 shadow transition hover:shadow-md focus:outline-none focus:ring-2 focus:ring-indigo-500"
                    >
                        <h3 class="text-lg font-semibold text-gray-900">
                            {{ recipe.title }}
                        </h3>
                        <p class="mt-2 line-clamp-2 text-sm text-gray-600">
                            {{ recipe.description }}
                        </p>
                        <dl
                            class="mt-4 flex flex-wrap gap-x-6 gap-y-1 text-sm text-gray-500"
                        >
                            <div class="flex gap-1">
                                <dt class="font-medium">Сложность:</dt>
                                <dd>{{ difficultyLabels[recipe.difficulty] }}</dd>
                            </div>
                            <div class="flex gap-1">
                                <dt class="font-medium">Время:</dt>
                                <dd>{{ recipe.cooking_time }} мин</dd>
                            </div>
                            <div class="flex gap-1">
                                <dt class="font-medium">Порций:</dt>
                                <dd>{{ recipe.servings }}</dd>
                            </div>
                        </dl>
                    </Link>
                </div>

                <nav
                    v-if="recipes.meta.last_page > 1"
                    class="mt-8 flex items-center justify-between"
                    aria-label="Пагинация"
                >
                    <Link
                        v-if="recipes.meta.prev_page_url !== null"
                        :href="recipes.meta.prev_page_url"
                        class="rounded-md bg-white px-4 py-2 text-sm text-gray-700 shadow transition hover:bg-gray-100"
                    >
                        Назад
                    </Link>
                    <span
                        v-else
                        class="rounded-md px-4 py-2 text-sm text-gray-400"
                    >
                        Назад
                    </span>

                    <span class="text-sm text-gray-600">
                        Страница {{ recipes.meta.current_page }} из
                        {{ recipes.meta.last_page }}
                    </span>

                    <Link
                        v-if="recipes.meta.next_page_url !== null"
                        :href="recipes.meta.next_page_url"
                        class="rounded-md bg-white px-4 py-2 text-sm text-gray-700 shadow transition hover:bg-gray-100"
                    >
                        Вперёд
                    </Link>
                    <span
                        v-else
                        class="rounded-md px-4 py-2 text-sm text-gray-400"
                    >
                        Вперёд
                    </span>
                </nav>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
