<template>
  <div class="max-w-3xl mx-auto p-6">
    <h1 class="text-3xl font-bold mb-8 text-center">Добавить новый рецепт</h1>
    
    <form @submit.prevent="submitForm" class="space-y-6">
      <!-- Разделы формы --> 
      <template
        v-for="field in formFields"
        :key="field.type"
      >
        <div class="bg-white p-6 rounded-lg shadow-md">
          <template v-if="field?.type === 'main'">
            <h2 class="text-xl font-semibold mb-4">{{ field.title }}</h2>
            <div
              v-if="field?.items?.length"
              class="grid grid-cols-1 md:grid-cols-2 gap-[20px]"
            >
              <template
                v-for="item in field.items"
                :key="item.name"
              >
              <div
                :class="{
                  'col-span-2': item.type === 'textarea' || item.name === 'imageUrl'
                }"
              >
                <div
                  class="text-lg font-semibold mb-2 w-full"
                >
                    {{ item.title }}
                </div>
                <form-input
                  v-if="['text', 'textarea', 'number'].includes(item.type)"
                  v-model="form[item.name]"
                  :type="item.type"
                  class="w-full"
                />

                <input-select
                  v-if="item.type === 'select'"
                  v-model="form[item.name]"
                  :options="item.options"
                  class="w-full"
                />
              </div>
              </template>
            </div>
          </template>
        </div>
      </template>

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

const formFields = [
  {
    "title": "Основная информация",
    "type": "main",
    "items": [
        {
            "type": "text",
            "name": "title",
            "title": "Имя",
            "required": true
        },
        {
            "type": "number",
            "name": "cookingTime",
            "title": "Телефон",
            "required": true
        },
        {
            "type": "number",
            "name": "servings",
            "title": "Количество порций",
            "required": true
        },
        {
            "type": "select",
            "name": "difficulty",
            "title": "Сложность",
            "options": [
                {
                    "value": "легко",
                    "title": "Легкая"
                },
                {
                    "value": "средне",
                    "title": "Средняя"
                },
                {
                    "value": "сложно",
                    "title": "Сложная"
                }
            ],
            "required": true
        },
        {
            "type": "textarea",
            "name": "description",
            "title": "Описание",
            "required": true
        },
        {
            "type": "text",
            "name": "imageUrl",
            "title": "Ссылка на изображение",
            "required": true
        }
    ]
  },
  {
    "title": "Ингредиенты",
    "type": "ingredients",
    "items": [
        {
            "type": "text",
            "name": "name",
            "title": "Название",
            "required": true
        },
        {
            "type": "number",
            "name": "quantity",
            "title": "Количество",
            "required": true
        },
        {
            "type": "select",
            "name": "unit",
            "title": "Единица измерения",
            "options": units,
            "required": true
        }
    ]
  },
  {
    "title": "Шаги приготовления",
    "type": "steps",
    "items": [
        {
            "type": "textarea",
            "name": "name",
            "title": "Шаг",
            "required": true
        }
    ]
  }
];

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