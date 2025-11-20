<template>
<div class="w-[95vw] max-w-[900px] mx-auto p-6">
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
  
  <UForm
    class="flex flex-col gap-[20px]"
    :key="formKey"
    :schema="schema"
    :state="form"
    :submit="submitForm"
  >
    <template v-for="group in mockFormField">
      <div
        class="p-[20px] rounded-[10px] shadow-[0_0_10px_0_rgba(0,0,0,0.1)]"
      >
        <div class="flex justify-between items-center mb-[30px]">
          <h2 class="text-[24px] font-bold">{{ group.title }}</h2>
          <UButton
            v-if="group.type === 'ingredients'"
            @click="addIngredient"
            color="primary"
            icon="i-heroicons-plus"
            size="sm"
            label="Добавить ингредиент"
          />
          <UButton
            v-if="group.type === 'steps'"
            @click="addStep"
            color="primary"
            icon="i-heroicons-plus"
            size="sm"
            label="Добавить шаг"
          />
        </div>
        <div>
          <!-- Regular form fields -->
          <div 
            v-if="group.type !== 'ingredients' && group.type !== 'steps'"
            class="grid grid-cols-12 gap-4"
          >
            <template v-for="item in group.items" :key="item.name">
              <UFormField 
                :label="item.title"
                :error="fieldErrors[item.name]"
                class="col-span-6"
              >
                <UInput
                  v-if="item.type !== 'select'"
                  v-model="form[item.name]"
                  :name="item.name"
                  :label="item.title"
                  :type="item.type"
                  :required="item.required"
                  :placeholder="item.placeholder"
                  class="w-full"
                />
                <USelect
                  v-else-if="item.type === 'select' && 'options' in item"
                  v-model="form[item.name]"
                  :items="item.options"
                  :name="item.name"
                  :label="item.title"
                  :required="item.required"
                  :placeholder="item.placeholder"
                  class="w-full"
                />
              </UFormField>
            </template>
          </div>

          <!-- Ingredients section -->
          <div v-if="group.type === 'ingredients'">
            <template 
              v-for="(ingredient, ingIndex) in form.ingredients" 
              :key="ingIndex" 
            >
              <UForm
                nested
                :state="ingredient"
                :name="`ingredient-${ingIndex}`"
                class="grid grid-cols-12 gap-4 mb-4 items-start"
              >
                <template 
                  v-for="field in group.ingredients[0]?.items" 
                  :key="field.name"
                >
                  <UFormField
                    :class="{
                      'col-span-5': field.name === 'name',
                      'col-span-4': field.name === 'quantity',
                      'col-span-2': field.name === 'unit'
                    }"
                    :error="fieldErrors[`ingredients.${ingIndex}.${field.name}`]"
                  >
                    <UInput
                      v-if="field.type !== 'select'"
                      v-model="ingredient[field.name]"
                      :name="`ingredient-${ingIndex}-${field.name}`"
                      :label="field.title"
                      :type="field.type"
                      :required="field.required"
                      :placeholder="field.placeholder"
                      class="w-full"
                    />
                    <USelect
                      v-else-if="field.type === 'select' && 'options' in field"
                      v-model="ingredient[field.name]"
                      :items="field.options"
                      :name="`ingredient-${ingIndex}-${field.name}`"
                      :label="field.title"
                      :required="field.required"
                      :placeholder="field.placeholder"
                      :type="field.type"
                      class="w-full"
                    />
                  </UFormField>
                </template>

                <UButton
                  v-if="form.ingredients.length > 1"
                  @click="removeIngredient(ingIndex)"
                  color="red"
                  variant="ghost"
                  icon="i-heroicons-trash"
                  size="sm"
                  class="col-span-1"
                />
              </UForm>
            </template>
          </div>

          <!-- Steps section -->
          <div v-if="group.type === 'steps'" class="flex flex-col gap-4">
            <div 
              v-for="(step, index) in form.steps" 
              :key="index" 
              class="flex items-start gap-2"
            >
              <UFormField 
                :error="fieldErrors[`steps.${index}`]"
                class="flex-1"
              >
                <UInput
                  v-model="form.steps[index]"
                  :name="`step-${index}`"
                  :label="`Шаг ${index + 1}`"
                  type="text"
                  required
                  :placeholder="`Опишите шаг ${index + 1}`"
                  class="w-full"
                />
              </UFormField>
              <UButton
                v-if="form.steps.length > 1"
                @click="removeStep(index)"
                color="red"
                variant="ghost"
                icon="i-heroicons-trash"
                size="sm"
              />
            </div>
          </div>
        </div>
      </div>
    </template>

    <div 
      class="flex justify-end gap-4"
    >
      <UButton
        @click="resetForm"
        color="red"
        label="Сбросить"
        class="bg-red-300 text-white hover:bg-red-500 hover:cursor-pointer"
      />
      <UButton
        type="submit"
        color="primary"
        @click="submitForm"
        :disabled="isSubmitting"
        :label="isSubmitting ? 'Загрузка...' : 'Добавить рецепт'"
        class="hover:cursor-pointer"
      />
    </div>
  </UForm>
</div>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import type { Recipe, Ingredient, Unit, FormGroup, SelectField } from '~/types';
import { useRecipes } from '~/composables/useRecipes';
import { mockFormField } from '~/data/mockFormField';
import * as z from 'zod';

const router = useRouter();
const { addRecipe } = useRecipes();

const units: Unit[] = ['г', 'кг', 'мл', 'л', 'шт', 'ст.л.', 'ч.л.', 'по вкусу'] as const;
const isSubmitting = ref(false);
const fieldErrors = ref<Record<string, string>>({});

const schema = z.object({
  title: z.string().min(1, 'Название блюда обязательно'),
  description: z.string().min(1, 'Описание блюда обязательно'),
  cookingTime: z.number().min(1, 'Время приготовления обязательно'),
  servings: z.number().min(1, 'Количество порций обязательно'),
  difficulty: z.enum(['легко', 'средне', 'сложно'], { message: 'Сложность обязательно'}),
  ingredients: z.array(z.object({
    name: z.string().min(1, 'Название ингредиента обязательно'),
    quantity: z.number().min(1, 'Количество обязательно'),
    unit: z.enum(units, { message: 'Единица измерения обязательно'})
  })),
  steps: z.array(z.string().min(1, 'Шаг обязательно')),
  // z.union для валидации url или null
  imageUrl: z.union(
      [
        z.string().url('Неверная ссылка на изображение'), 
        z.null()
      ]
  ).optional()
});

const form = ref<Omit<Recipe, 'id'>>({
  title: '',
  description: '',
  cookingTime: 30,
  servings: 2,
  difficulty: 'легко',
  ingredients: [
    { name: '', quantity: 0, unit: 'г' as const }
  ],
  steps: [''],
  imageUrl: null
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

const formKey = ref<number>(0);

const resetForm = () => {
  if (confirm('Вы уверены, что хотите сбросить форму? Все введенные данные будут потеряны.')) {
    form.value = {
      title: '',
      description: '',
      cookingTime: 30,
      servings: 2,
      difficulty: 'легко',
      ingredients: [
        { name: '', quantity: 0, unit: 'г' as const }
      ],
      steps: [''],
      imageUrl: ''
    };
    formKey.value++;
  }
};

const submitForm = async () => {
  try {
    isSubmitting.value = true;

    // Проверяем валидность формы
    const result = schema.safeParse(form.value);
    if (!result.success) {
      // console.log(result.error);

      result.error.issues.forEach((err) => {
        const key = err.path.join('.');
        fieldErrors.value[key] = err.message;
      });
      return;
    }    

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