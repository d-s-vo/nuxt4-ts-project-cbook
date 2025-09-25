<template>
    <div class="max-w-4xl mx-auto p-4">
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

        <div v-if="recipe" class="bg-white rounded-lg border overflow-hidden">
            <img 
                v-if="recipe.imageUrl" 
                :src="recipe.imageUrl" 
                :alt="recipe.title"
                class="w-full max-h-[300px] object-contain"
            >
            
            <div class="p-[20px] flex flex-col gap-[30px]">

                <h1 class="text-[32px] font-bold">{{ recipe.title }}</h1>
                
                <div class="flex flex-wrap gap-[20px]">
                    <div class="flex items-center gap-[5px]">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        {{ recipe.cookingTime }} минут
                    </div>
                    <div class="flex items-center gap-[5px]">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                        </svg>
                        {{ recipe.difficulty }}
                    </div>
                </div>

                <div v-if="recipe.description">
                    <p class="text-lg">{{ recipe.description }}</p>
                </div>

                <div v-if="recipe.ingredients && recipe.ingredients.length > 0">
                    <h2 class="text-2xl font-semibold mb-[20px]">Ингредиенты</h2>
                    <ul class="list-disc pl-5 space-y-2">
                        <li v-for="(ingredient, index) in recipe.ingredients" :key="index">
                            {{ ingredient.name }} {{ ingredient.quantity }} {{ ingredient.unit }}
                        </li>
                    </ul>
                </div>

                <div v-if="recipe.steps">
                    <h2 class="text-2xl font-semibold mb-[20px]">Способ приготовления</h2>
                    <ol class="list-decimal pl-5 space-y-2">
                        <li v-for="(step, index) in recipe.steps" :key="index">
                            {{ step }}
                        </li>
                    </ol>
                </div>
            </div>

            <div
                @click="deleteRecipe(recipe.id) && router.push('/')"
                class="
                    text-red-600 cursor-pointer mx-auto w-[60%] mb-[20px]
                    rounded-[20px] hover:bg-red-600 hover:text-white transition-colors
                    p-[20px] text-[24px] font-semibold text-center
                "
            >
                Удалить рецепт
            </div>
        </div>

        <div v-else-if="loading" class="text-center py-12">
            <p class="text-xl">Загрузка рецепта...</p>
        </div>
        
        <div v-else class="text-center py-12">
            <p class="text-xl text-gray-600">Рецепт не найден</p>
        </div>
    </div>
</template>

<script setup lang="ts">
import { useRecipes } from '~/composables/useRecipes';
import type { Recipe } from '~/types';
import { ref, onMounted, watch, nextTick } from 'vue';
import { useRoute, useRouter } from 'vue-router';

const route = useRoute();
const router = useRouter();
const { getRecipeById, deleteRecipe } = useRecipes();

const recipe = ref<Recipe | null>(null);
const loading = ref(true);

// Загружаем данные рецепта при монтировании компонента
onMounted(() => {
    loadRecipe();
});

// Следим за изменением query параметра
watch(() => route.query.id, () => {
    loadRecipe();
});

const loadRecipe = async () => {
    const recipeId = Number(route.query.id);
    loading.value = true;
    
    await nextTick();

    if (!isNaN(recipeId)) {
        const foundRecipe = getRecipeById(recipeId);
        recipe.value = foundRecipe || null;
        loading.value = false;
    } else {
        loading.value = false;
    }
};
</script>
