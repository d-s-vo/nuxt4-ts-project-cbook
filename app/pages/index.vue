<template>
    <div class="max-w-[800px] w-full mx-auto relative pb-[60px]">
        <div
            class="flex items-center justify-between gap-[20px] bg-[#f0f0f0] dark:bg-[#333] p-[20px]"
            :class="{ 'sticky top-[110px]': isEnabled }"
        >
            <h1 class="des:text-[36px] mob:text-[14px] font-bold underline font-dancing leading-[1.3]">
                Recipes:
            </h1>

            <input
                type="text"
                v-model="searchQuery"
                placeholder="Search recipes..."
                class="p-[10px] border-[1px] border-[#e0e0e0] rounded-[10px] dark:border-[#555] dark:bg-[#222]"
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

            <div class="flex items-center justify-between">
                <a href="/add-recipe" class="text-[#007bff] hover:underline">Add new recipe</a>

                <button @click="toggleTheme" class="text-[#007bff] hover:underline">Toggle theme</button>
            </div>
        </div>
    </div>
</template>
<script setup lang="ts">
import { ref, computed } from "vue";
import type { Recipe } from '~~/shared/types/recipe.types';
import { useColorMode } from "@vueuse/core";

const config = useRuntimeConfig()

useSeoMeta({
  titleTemplate: '%s | CBook — Кулинарная книга',
  title: 'Главная',
  description: 'Проверенные пошаговые рецепты',

  ogType: 'website',
  ogSiteName: 'CBook',
  ogTitle: 'CBook — Современная кулинарная книга',
  ogDescription: 'Проверенные пошаговые рецепты',
  ogImage: `${config.public.siteUrl}/images/og-default.jpg`,
  ogUrl: config.public.siteUrl,
  ogLocale: 'ru_RU',

  twitterCard: 'summary_large_image',
  twitterTitle: 'CBook — Современная кулинарная книга',
  twitterDescription: 'Проверенные пошаговые рецепты',
  twitterImage: `${config.public.siteUrl}/images/og-default.jpg`,
})

const { getAllRecipes } = useRecipes();
const { data: recipes, pending } = await getAllRecipes();

const { isEnabled } = useBvi();

const searchQuery = ref('');

const filteredRecipes = computed<Recipe[]>(() => {
    const query = searchQuery.value.trim().toLowerCase();
    
    // Безопасная проверка: если данных еще нет, отдаем пустой массив
    if (!query || !recipes.value) return recipes.value || [];

    const id = parseInt(query);
    if (!isNaN(id)) {
        // Явно указываем (r: Recipe)
        const recipe = recipes.value.find((r: Recipe) => r.id === id);
        return recipe ? [recipe] : [];
    }
    
    // Явно указываем (r: Recipe)
    return recipes.value.filter((r: Recipe) => r.title.toLowerCase().includes(query));
});

const colorMode = useColorMode();
const toggleTheme = () => {
  colorMode.value = colorMode.value === 'dark' ? 'light' : 'dark'
}
</script>