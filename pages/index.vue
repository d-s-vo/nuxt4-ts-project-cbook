<template>
    <div
        class="
            max-w-[800px] mx-auto
        "
    >
        <h1
            class="
                text-[36px] font-bold underline font-dancing
                leading-[1.3] bg-[#f0f0f0] p-[20px]
            "
        >
            Recipes:
        </h1>

        <div>{{ recipes }}</div>
    </div>
</template>
<script setup lang="ts">
import { useRecipes } from "~/composables/useRecipes";
const {
    getAllRecipes,
    getRecipesByTitle,
    getRecipeById,
    deleteRecipe,
    addRecipe,
    recipes
} = useRecipes();
</script>