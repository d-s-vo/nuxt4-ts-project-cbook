import type { Recipe } from '~/shared/types/recipe.types'

export const useRecipes = () => {

    // Получить все рецепты
    const getAllRecipes = async () => {
        return useFetch<Recipe[]>('/api/recipes');
    }
    
    // Получить один рецепт по ID
    const getRecipeById = async (id: number) => {
        return await $fetch<Recipe>(`/api/recipes/${id}`)
    }

    // Удалить рецепт
    const deleteRecipe = async (id: number) => {
        return await $fetch(`/api/recipes/${id}`, {
            method: 'DELETE'
        })
    }

    return {
        getAllRecipes,
        getRecipeById,
        deleteRecipe
    }
}