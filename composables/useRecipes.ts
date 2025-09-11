import type { Ref } from 'vue';
import { computed, ref } from 'vue';
import type { Recipe } from '../types';
import { mockRecipes } from '../data/mockRecipes';

export const useRecipes = () => {
    // Реактивное состояние для хранения рецептов
    const recipes: Ref<Recipe[]> = ref([...mockRecipes]);

    // Получить все рецепты
    const getAllRecipes = computed(() => recipes.value);

    // Добавить рецепт
    const addRecipe = (newRecipe: Omit<Recipe, 'id'>): void => {
        const newId = Math.max(0, ...recipes.value.map(r => r.id)) + 1 as number;
        recipes.value.push({
            ...newRecipe,
            id: newId
        })
    };

    // Поиск рецепта по id
    const getRecipeById = (id: number): Recipe | undefined => {
        return recipes.value.find(recipe => recipe.id === id);
    }

    // Поиск рецепта по названию
    const getRecipesByTitle = (query: string): Recipe[] => {
        if (!query.trim()) return [];
        const searchTerm = query.toLowerCase();
        return recipes.value.filter(recipe => recipe.title.toLocaleLowerCase().includes(searchTerm) || 
        recipe.description.toLocaleLowerCase().includes(searchTerm));
    }

    // Удалить рецепт
    const deleteRecipe = (id: number): void => {
        recipes.value = recipes.value.filter(recipe => recipe.id !== id);
    }

    return {
        getAllRecipes,
        addRecipe,
        getRecipeById,
        getRecipesByTitle,
        deleteRecipe,
        recipes
    }
}