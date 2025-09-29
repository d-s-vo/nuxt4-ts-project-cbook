<template>
    <div
        v-if="recipe"
        @click.prevent="navigateToRecipe(recipe.id)"
        class="cursor-pointer group"
    >
        <div
            class="flex gap-[20px] group-hover:bg-[#f0f0f0] p-[20px] rounded-[10px]"
        >
            <img
                v-if="recipe.imageUrl" 
                :src="recipe.imageUrl" 
                :alt="recipe.title"
                class="max-w-[100px] aspect-square shrink-0 rounded-[10px]"
            >
            <div>
                <h2
                    v-if="recipe?.title"
                    class="text-[24px] font-bold group-hover:underline"
                >
                    {{ recipe.title }}
                </h2>
                <div>Время приготовления: {{ recipe.cookingTime }} минут</div>
                <div>Сложность: {{ recipe.difficulty }}</div>
            </div>
        </div>
    </div>
</template>
<script setup lang="ts">
import type { Recipe } from '~/types';
import { useRouter } from 'vue-router';

const router = useRouter();

defineProps<{
    recipe: Recipe;
}>();

const navigateToRecipe = (id: number) => {
    router.push(`/recipe-page?id=${id}`);
};
</script>