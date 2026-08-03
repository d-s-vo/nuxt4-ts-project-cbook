<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import DangerButton from '@/Components/DangerButton.vue';

const props = defineProps<{
    recipe: App.Data.RecipeData;
    canUpdate: boolean;
}>();

const difficultyLabels: Record<App.Enums.Difficulty, string> = {
    low: 'Простой',
    medium: 'Средний',
    high: 'Сложный',
};

const deleteForm = useForm({});

const destroy = () => {
    if (!window.confirm('Удалить этот рецепт?')) {
        return;
    }

    deleteForm.delete(route('recipes.destroy', props.recipe.id));
};
</script>

<template>
    <Head :title="recipe.title" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between gap-4">
                <h2 class="text-xl font-semibold leading-tight text-gray-800">
                    {{ recipe.title }}
                </h2>
                <div v-if="canUpdate" class="flex gap-2">
                    <Link
                        :href="route('recipes.edit', recipe.id)"
                        class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                    >
                        Редактировать
                    </Link>
                    <DangerButton
                        :disabled="deleteForm.processing"
                        @click="destroy"
                    >
                        Удалить
                    </DangerButton>
                </div>
            </div>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-3xl space-y-8 px-4 sm:px-6 lg:px-8">
                <div class="rounded-lg bg-white p-6 shadow">
                    <dl
                        class="flex flex-wrap gap-x-8 gap-y-2 text-sm text-gray-600"
                    >
                        <div class="flex gap-1">
                            <dt class="font-medium">Сложность:</dt>
                            <dd>{{ difficultyLabels[recipe.difficulty] }}</dd>
                        </div>
                        <div class="flex gap-1">
                            <dt class="font-medium">Время приготовления:</dt>
                            <dd>{{ recipe.cooking_time }} мин</dd>
                        </div>
                        <div class="flex gap-1">
                            <dt class="font-medium">Порций:</dt>
                            <dd>{{ recipe.servings }}</dd>
                        </div>
                    </dl>
                    <p class="mt-4 whitespace-pre-line text-gray-800">
                        {{ recipe.description }}
                    </p>
                </div>

                <section class="rounded-lg bg-white p-6 shadow">
                    <h3 class="text-lg font-semibold text-gray-900">
                        Ингредиенты
                    </h3>
                    <ul class="mt-4 divide-y divide-gray-100">
                        <li
                            v-for="ingredient in recipe.ingredients"
                            :key="ingredient.id"
                            class="flex justify-between py-2 text-sm text-gray-700"
                        >
                            <span>{{ ingredient.name }}</span>
                            <span class="text-gray-500">
                                {{ ingredient.quantity }} {{ ingredient.unit }}
                            </span>
                        </li>
                    </ul>
                </section>

                <section class="rounded-lg bg-white p-6 shadow">
                    <h3 class="text-lg font-semibold text-gray-900">
                        Приготовление
                    </h3>
                    <ol class="mt-4 space-y-4">
                        <li
                            v-for="(step, index) in recipe.steps"
                            :key="index"
                            class="flex gap-3 text-gray-800"
                        >
                            <span
                                class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-indigo-100 text-sm font-semibold text-indigo-700"
                            >
                                {{ index + 1 }}
                            </span>
                            <p class="pt-0.5">{{ step }}</p>
                        </li>
                    </ol>
                </section>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
