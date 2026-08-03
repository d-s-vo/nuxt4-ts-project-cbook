<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import TextInput from '@/Components/TextInput.vue';

interface RecipeForm {
    title: string;
    description: string;
    cooking_time: number;
    servings: number;
    difficulty: App.Enums.Difficulty;
    steps: string[];
    ingredients: Array<{ name: string; quantity: number; unit: string }>;
}

const difficultyOptions: Array<{ value: App.Enums.Difficulty; label: string }> =
    [
        { value: 'low', label: 'Простой' },
        { value: 'medium', label: 'Средний' },
        { value: 'high', label: 'Сложный' },
    ];

const form = useForm<RecipeForm>({
    title: '',
    description: '',
    cooking_time: 30,
    servings: 2,
    difficulty: 'medium',
    steps: [''],
    ingredients: [{ name: '', quantity: 1, unit: '' }],
});

const addStep = () => form.steps.push('');
const removeStep = (index: number) => form.steps.splice(index, 1);

const addIngredient = () =>
    form.ingredients.push({ name: '', quantity: 1, unit: '' });
const removeIngredient = (index: number) =>
    form.ingredients.splice(index, 1);

const submit = () => form.post(route('recipes.store'));
</script>

<template>
    <Head title="Новый рецепт" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Новый рецепт
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8">
                <form
                    class="space-y-8 rounded-lg bg-white p-6 shadow"
                    @submit.prevent="submit"
                >
                    <div>
                        <InputLabel for="title" value="Название" />
                        <TextInput
                            id="title"
                            v-model="form.title"
                            type="text"
                            class="mt-1 block w-full"
                            required
                            autofocus
                        />
                        <InputError class="mt-2" :message="form.errors.title" />
                    </div>

                    <div>
                        <InputLabel for="description" value="Описание" />
                        <textarea
                            id="description"
                            v-model="form.description"
                            rows="3"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            required
                        />
                        <InputError
                            class="mt-2"
                            :message="form.errors.description"
                        />
                    </div>

                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-3">
                        <div>
                            <InputLabel
                                for="cooking_time"
                                value="Время (мин)"
                            />
                            <input
                                id="cooking_time"
                                v-model.number="form.cooking_time"
                                type="number"
                                min="1"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                required
                            />
                            <InputError
                                class="mt-2"
                                :message="form.errors.cooking_time"
                            />
                        </div>

                        <div>
                            <InputLabel for="servings" value="Порций" />
                            <input
                                id="servings"
                                v-model.number="form.servings"
                                type="number"
                                min="1"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                required
                            />
                            <InputError
                                class="mt-2"
                                :message="form.errors.servings"
                            />
                        </div>

                        <div>
                            <InputLabel for="difficulty" value="Сложность" />
                            <select
                                id="difficulty"
                                v-model="form.difficulty"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            >
                                <option
                                    v-for="option in difficultyOptions"
                                    :key="option.value"
                                    :value="option.value"
                                >
                                    {{ option.label }}
                                </option>
                            </select>
                            <InputError
                                class="mt-2"
                                :message="form.errors.difficulty"
                            />
                        </div>
                    </div>

                    <div>
                        <div class="flex items-center justify-between">
                            <h3 class="text-base font-semibold text-gray-900">
                                Шаги приготовления
                            </h3>
                            <SecondaryButton type="button" @click="addStep">
                                Добавить шаг
                            </SecondaryButton>
                        </div>
                        <div class="mt-4 space-y-3">
                            <div
                                v-for="(_step, index) in form.steps"
                                :key="index"
                                class="flex items-start gap-2"
                            >
                                <span
                                    class="mt-2 w-6 shrink-0 text-sm font-medium text-gray-500"
                                >
                                    {{ index + 1 }}.
                                </span>
                                <div class="flex-1">
                                    <TextInput
                                        v-model="form.steps[index]"
                                        type="text"
                                        class="block w-full"
                                        required
                                    />
                                    <InputError
                                        class="mt-1"
                                        :message="
                                            form.errors[`steps.${index}`]
                                        "
                                    />
                                </div>
                                <button
                                    v-if="form.steps.length > 1"
                                    type="button"
                                    class="mt-2 text-sm text-red-600 hover:text-red-500"
                                    @click="removeStep(index)"
                                >
                                    Удалить
                                </button>
                            </div>
                        </div>
                        <InputError class="mt-2" :message="form.errors.steps" />
                    </div>

                    <div>
                        <div class="flex items-center justify-between">
                            <h3 class="text-base font-semibold text-gray-900">
                                Ингредиенты
                            </h3>
                            <SecondaryButton
                                type="button"
                                @click="addIngredient"
                            >
                                Добавить ингредиент
                            </SecondaryButton>
                        </div>
                        <div class="mt-4 space-y-4">
                            <div
                                v-for="(ingredient, index) in form.ingredients"
                                :key="index"
                                class="grid grid-cols-1 gap-3 sm:grid-cols-[1fr,7rem,6rem,auto] sm:items-start"
                            >
                                <div>
                                    <TextInput
                                        v-model="ingredient.name"
                                        type="text"
                                        class="block w-full"
                                        placeholder="Название"
                                        required
                                    />
                                    <InputError
                                        class="mt-1"
                                        :message="
                                            form.errors[
                                                `ingredients.${index}.name`
                                            ]
                                        "
                                    />
                                </div>
                                <div>
                                    <input
                                        v-model.number="ingredient.quantity"
                                        type="number"
                                        min="0"
                                        step="0.01"
                                        placeholder="Кол-во"
                                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                        required
                                    />
                                    <InputError
                                        class="mt-1"
                                        :message="
                                            form.errors[
                                                `ingredients.${index}.quantity`
                                            ]
                                        "
                                    />
                                </div>
                                <div>
                                    <TextInput
                                        v-model="ingredient.unit"
                                        type="text"
                                        class="block w-full"
                                        placeholder="Ед."
                                        required
                                    />
                                    <InputError
                                        class="mt-1"
                                        :message="
                                            form.errors[
                                                `ingredients.${index}.unit`
                                            ]
                                        "
                                    />
                                </div>
                                <button
                                    v-if="form.ingredients.length > 1"
                                    type="button"
                                    class="text-sm text-red-600 hover:text-red-500 sm:pt-2"
                                    @click="removeIngredient(index)"
                                >
                                    Удалить
                                </button>
                            </div>
                        </div>
                        <InputError
                            class="mt-2"
                            :message="form.errors.ingredients"
                        />
                    </div>

                    <div class="flex justify-end">
                        <PrimaryButton :disabled="form.processing">
                            Сохранить рецепт
                        </PrimaryButton>
                    </div>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
