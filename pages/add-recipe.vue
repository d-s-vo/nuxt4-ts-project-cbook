<template>
  <div class="max-w-3xl mx-auto p-6">
    <h1 class="text-3xl font-bold mb-8 text-center">Добавить новый рецепт</h1>
    
    <form @submit.prevent="submitForm" class="space-y-6">
      <!-- Основная информация -->
      <div class="bg-white p-6 rounded-lg shadow-md">
        <h2 class="text-xl font-semibold mb-4">Основная информация</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div>
            <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Название рецепта *</label>
            <input
              id="title"
              v-model="form.title"
              type="text"
              required
              class="w-full p-2 border rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent"
            >
          </div>

          <div>
            <label for="cookingTime" class="block text-sm font-medium text-gray-700 mb-1">Время приготовления (минут) *</label>
            <input
              id="cookingTime"
              v-model.number="form.cookingTime"
              type="number"
              min="1"
              required
              class="w-full p-2 border rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent"
            >
          </div>

          <div>
            <label for="servings" class="block text-sm font-medium text-gray-700 mb-1">Количество порций *</label>
            <input
              id="servings"
              v-model.number="form.servings"
              type="number"
              min="1"
              required
              class="w-full p-2 border rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent"
            >
          </div>

          <div>
            <label for="difficulty" class="block text-sm font-medium text-gray-700 mb-1">Сложность *</label>
            <select
              id="difficulty"
              v-model="form.difficulty"
              required
              class="w-full p-2 border rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent"
            >
              <option value="легко">Легко</option>
              <option value="средне">Средне</option>
              <option value="сложно">Сложно</option>
            </select>
          </div>
        </div>

        <div class="mt-4">
          <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Описание *</label>
          <textarea
            id="description"
            v-model="form.description"
            rows="3"
            required
            class="w-full p-2 border rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent"
          ></textarea>
        </div>

        <div class="mt-4">
          <label for="imageUrl" class="block text-sm font-medium text-gray-700 mb-1">Ссылка на изображение</label>
          <input
            id="imageUrl"
            v-model="form.imageUrl"
            type="text"
            placeholder="/images/recipe.jpg"
            class="w-full p-2 border rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent"
          >
        </div>
      </div>

      <!-- Ингредиенты -->
      <div class="bg-white p-6 rounded-lg shadow-md">
        <div class="flex justify-between items-center mb-4">
          <h2 class="text-xl font-semibold">Ингредиенты</h2>
          <button
            type="button"
            @click="addIngredient"
            class="px-4 py-2 bg-green-500 text-white rounded-md hover:bg-green-600 transition-colors"
          >
            + Добавить ингредиент
          </button>
        </div>

        <div v-for="(ingredient, index) in form.ingredients" :key="index" class="grid grid-cols-12 gap-3 mb-3 items-end">
          <div class="col-span-5">
            <label :for="'ingredient-name-' + index" class="block text-sm font-medium text-gray-700 mb-1">Название *</label>
            <input
              :id="'ingredient-name-' + index"
              v-model="ingredient.name"
              type="text"
              required
              class="w-full p-2 border rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent"
            >
          </div>
          
          <div class="col-span-2">
            <label :for="'ingredient-quantity-' + index" class="block text-sm font-medium text-gray-700 mb-1">Количество *</label>
            <input
              :id="'ingredient-quantity-' + index"
              v-model.number="ingredient.quantity"
              type="number"
              step="0.01"
              min="0"
              required
              class="w-full p-2 border rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent"
            >
          </div>
          
          <div class="col-span-3">
            <label :for="'ingredient-unit-' + index" class="block text-sm font-medium text-gray-700 mb-1">Единица измерения *</label>
            <select
              :id="'ingredient-unit-' + index"
              v-model="ingredient.unit"
              required
              class="w-full p-2 border rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent"
            >
              <option v-for="unit in units" :key="unit" :value="unit">{{ unit }}</option>
            </select>
          </div>
          
          <div class="col-span-2">
            <button
              type="button"
              @click="removeIngredient(index)"
              class="w-full p-2 bg-red-500 text-white rounded-md hover:bg-red-600 transition-colors"
              :disabled="form.ingredients.length <= 1"
              :class="{ 'opacity-50 cursor-not-allowed': form.ingredients.length <= 1 }"
            >
              Удалить
            </button>
          </div>
        </div>
      </div>

      <!-- Шаги приготовления -->
      <div class="bg-white p-6 rounded-lg shadow-md">
        <div class="flex justify-between items-center mb-4">
          <h2 class="text-xl font-semibold">Шаги приготовления</h2>
          <button
            type="button"
            @click="addStep"
            class="px-4 py-2 bg-green-500 text-white rounded-md hover:bg-green-600 transition-colors"
          >
            + Добавить шаг
          </button>
        </div>

        <div v-for="(step, index) in form.steps" :key="'step-' + index" class="mb-4">
          <label :for="'step-' + index" class="block text-sm font-medium text-gray-700 mb-1">Шаг {{ index + 1 }} *</label>
          <div class="flex gap-2">
            <textarea
              :id="'step-' + index"
              v-model="form.steps[index]"
              rows="2"
              required
              class="flex-1 p-2 border rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent"
            ></textarea>
            <button
              type="button"
              @click="removeStep(index)"
              class="p-2 bg-red-500 text-white rounded-md hover:bg-red-600 transition-colors self-end"
              :disabled="form.steps.length <= 1"
              :class="{ 'opacity-50 cursor-not-allowed': form.steps.length <= 1 }"
            >
              Удалить
            </button>
          </div>
        </div>
      </div>

      <!-- Кнопки формы -->
      <div class="flex justify-end space-x-4">
        <button
          type="button"
          @click="resetForm"
          class="px-6 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 transition-colors"
        >
          Сбросить
        </button>
        <button
          type="submit"
          class="px-6 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 transition-colors"
          :disabled="isSubmitting"
          :class="{ 'opacity-50 cursor-not-allowed': isSubmitting }"
        >
          {{ isSubmitting ? 'Сохранение...' : 'Сохранить рецепт' }}
        </button>
      </div>
    </form>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import type { Recipe, Ingredient, Unit } from '~/types';
import { useRecipes } from '~/composables/useRecipes';

const router = useRouter();
const { addRecipe } = useRecipes();

const units: Unit[] = ['г', 'кг', 'мл', 'л', 'шт', 'ст.л.', 'ч.л.', 'по вкусу'];
const isSubmitting = ref(false);

const form = ref<Omit<Recipe, 'id'>>({
  title: '',
  description: '',
  cookingTime: 30,
  servings: 2,
  difficulty: 'легко',
  ingredients: [
    { name: '', quantity: 0, unit: 'г' }
  ],
  steps: [''],
  imageUrl: ''
});

const addIngredient = () => {
  form.value.ingredients.push({ name: '', quantity: 0, unit: 'г' });
};

const removeIngredient = (index: number) => {
  if (form.value.ingredients.length > 1) {
    form.value.ingredients.splice(index, 1);
  }
};

const addStep = () => {
  form.value.steps.push('');
};

const removeStep = (index: number) => {
  if (form.value.steps.length > 1) {
    form.value.steps.splice(index, 1);
  }
};

const resetForm = () => {
  if (confirm('Вы уверены, что хотите сбросить форму? Все введенные данные будут потеряны.')) {
    form.value = {
      title: '',
      description: '',
      cookingTime: 30,
      servings: 2,
      difficulty: 'легко',
      ingredients: [
        { name: '', quantity: 0, unit: 'г' }
      ],
      steps: [''],
      imageUrl: ''
    };
  }
};

const submitForm = async () => {
  try {
    isSubmitting.value = true;

    // Добавляем рецепт через композабл
    addRecipe(form.value);
    
    // Перенаправляем на главную страницу
    await router.push('/');
    
    // Показываем уведомление об успешном добавлении
    alert('Рецепт успешно добавлен!');
  } catch (error) {
    console.error('Ошибка при сохранении рецепта:', error);
    alert('Произошла ошибка при сохранении рецепта. Пожалуйста, попробуйте снова.');
  } finally {
    isSubmitting.value = false;
  }
};
</script>

<style scoped>
/* Стили для улучшенного UX */
input[type="number"]::-webkit-inner-spin-button,
input[type="number"]::-webkit-outer-spin-button {
  -webkit-appearance: none;
  margin: 0;
}

/* Анимация появления/скрытия элементов */
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.3s ease;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
</style>