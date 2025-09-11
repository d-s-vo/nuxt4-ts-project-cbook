<template>
    <div class="max-w-[800px] mx-auto">
        <h1
            class="
                text-[36px] font-bold underline font-dancing
                leading-[1.3] bg-[#f0f0f0] p-[20px]
            "
        >
            Recipes:
        </h1>

        <div class="flex flex-col gap-[20px]">
            <template 
                v-for="recipe in getAllRecipes" 
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
        </div>
    </div>
</template>
<script setup lang="ts">
import { useRecipes } from "~/composables/useRecipes";

const {
    getAllRecipes,
    getRecipesByTitle,
    getRecipeById,
    deleteRecipe,
    addRecipe
} = useRecipes();
</script>