<template>
    <div
        v-if="recipe"
        @click.prevent="navigateToRecipe(recipe.id)"
        class="cursor-pointer group"
    >
        <div
            class="flex gap-[20px] group-hover:bg-[#f0f0f0] dark:group-hover:bg-[#333] p-[20px] rounded-[10px]"
        >
            <div class="w-[100px] aspect-square shrink-0">
                <img
                    v-if="recipe?.imageUrl" 
                    :src="recipe.imageUrl" 
                    :alt="recipe.title"
                    class="rounded-[10px] w-full h-full object-cover"
                >
            </div>
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
import type { Recipe } from '~~/shared/types';

defineProps<{
    recipe: Recipe;
}>();

const navigateToRecipe = async (id: number) => {
  // navigateTo доступен глобально, его не нужно импортировать
  await navigateTo({
    path: '/recipe-page',
    query: { id: id }
  })
}
</script>