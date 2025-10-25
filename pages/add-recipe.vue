<template>
  <div class="max-w-3xl mx-auto p-6">
    <a 
          href="/" 
          class="inline-flex items-center text-blue-600 hover:text-blue-800 mb-6 no-underline"
          @click.prevent="$router.back()"
      >
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
          </svg>
          Назад к рецептам
      </a>

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
                  :placeholder="item.placeholder"
                  class="w-full"
                />

                <input-select
                  v-if="item.type === 'select'"
                  v-model="form[item.name]"
                  :options="(item as SelectField<typeof item.name>).options"
                  :placeholder="item.placeholder"
                  class="w-full"
                />
              </div>
              </template>
            </div>
          </template>

          <!-- Раздел ингредиентов -->
          <template v-else-if="field?.type === 'ingredients'">
            <div class="flex justify-between items-center mb-[20px]">
              <h2 class="text-xl font-semibold">{{ field.title }}</h2>
              <button
                type="button"
                @click="addIngredient"
                class="px-4 py-2 bg-green-500 text-white rounded-md hover:bg-green-600 transition-colors text-sm"
              >
                + Добавить ингредиент
              </button>
            </div>
            
            <div
              class="relative mt-[20px] flex flex-col gap-y-[20px]"
            >
              <template
                v-for="(ingredient, index) in form.ingredients"
                :key="index"
              >
                <div>
                  <!-- Кнопка удаления -->
                    <icons-trash 
                    @click="removeIngredient(index)"
                    class="cursor-pointer hover:text-green-600 transition-all duration-300 ml-auto mb-[10px]"
                    :class="{ 'hidden': form.ingredients.length <= 1 }"
                  />

                  <div  class="grid grid-cols-1 md:grid-cols-12 gap-[15px]">
                    <template
                      v-if="field?.ingredients?.[0]?.items"
                      v-for="item in field?.ingredients[0].items"
                      :key="item.name"
                    >
                      <!-- Название ингредиента -->
                      <div
                        :class="{
                          'col-span-5': item.name === 'name',
                          'col-span-3': item.name === 'quantity',
                          'col-span-4': item.name === 'unit',
                        }"
                      >
                        <div class="text-sm font-medium mb-2">{{ item.title }}</div>
                        <form-input
                          v-if="item.name === 'name'"
                          v-model="ingredient.name"
                          :type="item.type"
                          :placeholder="item.placeholder"
                          class="w-full"
                        />
                      

                      <!-- Количество -->
                      <form-input
                        v-if="item.name === 'quantity'"
                        v-model="ingredient.quantity"
                        :type="item.type"
                        :placeholder="item.placeholder"
                        class="w-full"
                      />

                      <!-- Единица измерения -->
                      <input-select
                        v-if="item.name === 'unit'"
                        v-model="ingredient.unit"
                        :options="(item as SelectField).options"
                        class="w-full"
                      />
                    </div>
                    </template>
                  </div>
                </div>
              </template>
            </div>
          </template>

          <template v-else-if="field?.type === 'steps'">
            <div class="flex justify-between items-center mb-4">
              <h2 class="text-xl font-semibold">{{ field.title }}</h2>
              <button
                type="button"
                @click="addStep"
                class="px-4 py-2 bg-green-500 text-white rounded-md hover:bg-green-600 transition-colors text-sm"
              >
                + Добавить этап
              </button>
            </div>
            
            <div
              v-for="(step, index) in form.steps"
              :key="index"
              class="relative mt-[20px]"
            >
              <template
                v-if="field?.items?.length"
                v-for="(item, i) in field.items"
                :key="`${i}-${item.name}`"
              >
                <div class="flex items-start gap-4">
                  <!-- Поле для шага -->
                  <div class="flex-grow">
                    <div class="text-sm font-medium mb-2">Описание этапа</div>
                    <form-input
                      v-model="form.steps[index]"
                      :type="item.type"
                      :placeholder="item.placeholder"
                      class="w-full"
                      required
                    />
                  </div>

                    <!-- Кнопка удаления -->
                  <icons-trash 
                    @click="removeStep(index)"
                    class="absolute top-0 right-0 cursor-pointer hover:text-green-600"
                    :class="{ 'hidden': form.steps.length <= 1 }"
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
import type { Recipe, Ingredient, Unit, FormGroup, SelectField } from '~/types';
import { useRecipes } from '~/composables/useRecipes';

const router = useRouter();
const { addRecipe } = useRecipes();

const units: Unit[] = ['г', 'кг', 'мл', 'л', 'шт', 'ст.л.', 'ч.л.', 'по вкусу'];
const isSubmitting = ref(false);

const formFields: FormGroup[] = [
  {
    "title": "Основная информация",
    "type": "main",
    "items": [
        {
            "type": "text",
            "name": "title",
            "title": "Название блюда",
            "required": true,
            "placeholder": "Название блюда"
        },
        {
            "type": "number",
            "name": "cookingTime",
            "title": "Время приготовления",
            "required": true,
            "placeholder": "Время приготовления"  
        },
        {
            "type": "number",
            "name": "servings",
            "title": "Количество порций",
            "required": true,
            "placeholder": "Количество порций"
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
            "required": true,
            "placeholder": "Сложность"
        },
        {
            "type": "textarea",
            "name": "description",
            "title": "Описание блюда",
            "required": true,
            "placeholder": "Добавьте описание"
        },
        {
            "type": "text",
            "name": "imageUrl",
            "title": "Ссылка на изображение",
            "required": true,
            "placeholder": "Вставьте ссылку на изображение"
        }
    ]
  },
  {
    "title": "Ингредиенты",
    "type": "ingredients",
    "ingredients": [
      {
        "items": [
          {
            "type": "text",
            "name": "name",
            "title": "Название",
            "required": true,
            "placeholder": "Введите название ингредиента"
          },
          {
            "type": "number",
            "name": "quantity",
            "title": "Количество",
            "required": true,
            "placeholder": "5"
          },
          {
            "type": "select",
            "name": "unit",
            "title": "Единица измерения",
            "options": [
              { value: 'г', title: 'Граммы' },
              { value: 'кг', title: 'Килограммы' },
              { value: 'мл', title: 'Миллилитры' },
              { value: 'л', title: 'Литры' },
              { value: 'шт', title: 'Штуки' },
              { value: 'ст.л.', title: 'Столовые ложки' },
              { value: 'ч.л.', title: 'Чайные ложки' },
              { value: 'по вкусу', title: 'По вкусу' }
            ],
            "required": true
          }
        ]
      }
    ]
  },
  {
    "title": "Этапы приготовления",
    "type": "steps",
    "items": [
        {
          "type": "textarea",
          "name": "step",
          "title": "Этап",
          "required": true,
          "placeholder": "Опишите этап приготовления"
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