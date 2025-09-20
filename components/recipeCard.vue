<template>
    <div 
        class="flex gap-[20px] [@media(min-width: 768px)]:flex-col"
    >
        <img
            v-if="recipe.imageUrl" 
            :src="recipe.imageUrl" 
            alt="recipe.title"
            class="max-w-[100px] aspect-square shrink-0 rounded-[10px]"
        >
        <div>
            <h2
                v-if="recipe?.title"
                class="text-[24px] font-bold"
            >
                {{ recipe.title }}
            </h2>
            <div>Время приготовления: {{ recipe.cookingTime }} минут</div>
            <div>Сложность: {{ recipe.difficulty }}</div>
        </div>
    </div>
</template>
<script setup lang="ts">
import type { Recipe } from '~/types';

defineProps<{
    recipe: Recipe;
}>();
</script>