<template>
    <div class="max-w-[800px] mx-auto">
        <div
            class="flex items-center justify-between gap-[20px] bg-[#f0f0f0] p-[20px]"
        >
            <h1 class="text-[36px] font-bold underline font-dancing leading-[1.3]">
                Recipes:
            </h1>

            <input
                type="text"
                v-model="searchQuery"
                placeholder="Search recipes..."
                class="p-[10px] border-[1px] border-[#e0e0e0] rounded-[10px]"
            />
        </div>

        <div class="flex flex-col gap-[20px] mt-[20px] [@media(max-width:805px)]:px-[10px]">
            <template 
                v-for="recipe in filteredRecipes" 
                :key="recipe.id"
            >
                <recipe-card 
                    :recipe="recipe"
                    class="
                        [&:not(:first-child)]:border-t-[1px] [&:not(:first-child)]:pt-[20px]
                        border-[#e0e0e0]
                    "
                />
            </template>

            <a href="/add-recipe" class="text-[#007bff] hover:underline">Add new recipe</a>
        </div>
    </div>
</template>
<script setup lang="ts">
import { useRecipes } from "~/composables/useRecipes";
import { ref, computed } from "vue";
import type { Recipe } from '~/types';

const {recipes} = useRecipes();

const {
    getRecipesByTitle,
    getRecipeById
} = useRecipes();

const searchQuery = ref('');
const filteredRecipes = computed<Recipe[]>(() => {
    const query = searchQuery.value.trim();
    if (!query) return recipes.value;
    const id = parseInt(query);
    if (!isNaN(id)) {
        const recipe = getRecipeById(id);
        if (recipe) return [recipe];
    };
    return getRecipesByTitle(query);
});

</script>