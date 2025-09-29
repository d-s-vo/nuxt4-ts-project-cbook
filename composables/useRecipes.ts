import { computed, onMounted, watch, onUnmounted, ref } from 'vue';
import { useState } from 'nuxt/app';
import type { Recipe } from '../types';
import { mockRecipes } from '../data/mockRecipes';

const STORAGE_KEY = 'recipe-app-recipes';

export const useRecipes = () => {
    // Use a ref to track initialization
    const isInitialized = ref(false);
    
    // Use useState with a factory function for SSR support
    const recipes = useState<Recipe[]>('recipes', () => []);

    // Initialize recipes only once
    const initializeRecipes = () => {
        if (isInitialized.value) return;
        
        if (process.client) {
            try {
                const saved = localStorage.getItem(STORAGE_KEY);
                recipes.value = saved ? JSON.parse(saved) : [...mockRecipes];
            } catch (e) {
                console.error('Failed to load recipes:', e);
                recipes.value = [...mockRecipes];
            }
        } else {
            recipes.value = [...mockRecipes];
        }
        
        isInitialized.value = true;
    };

    // Save recipes to localStorage
    const saveToLocalStorage = () => {
        try {
            localStorage.setItem(STORAGE_KEY, JSON.stringify(recipes.value));
        } catch (e) {
            console.error('Failed to save recipes:', e);
        }
    };

    // Initialize on mount
    onMounted(() => {
        initializeRecipes();
        
        // Setup watcher
        const stopWatcher = watch(() => [...recipes.value], () => {
            saveToLocalStorage();
        }, { deep: true });

        // Cleanup on unmount
        onUnmounted(() => {
            stopWatcher();
        });
    });

    // Add new recipe with auto-increment ID
    const addRecipe = (newRecipe: Omit<Recipe, 'id'>): Recipe => {
        const newId = recipes.value.length > 0 
            ? Math.max(...recipes.value.map(r => r.id)) + 1 
            : 1;
        
        const recipe: Recipe = {
            ...newRecipe,
            id: newId,
            imageUrl: newRecipe.imageUrl || '/images/placeholder.jpg'
        };

        recipes.value = [...recipes.value, recipe];
        return recipe;
    };

    // Get recipe by ID
    const getRecipeById = (id: number): Recipe | undefined => {
        return recipes.value.find(recipe => recipe.id === id);
    };

    // Search recipes
    const getRecipesByTitle = (query: string): Recipe[] => {
        if (!query.trim()) return [];
        const searchTerm = query.toLowerCase();
        return recipes.value.filter(recipe => 
            recipe.title.toLowerCase().includes(searchTerm) || 
            recipe.description.toLowerCase().includes(searchTerm)
        );
    };

    // Delete recipe
    const deleteRecipe = (id: number): boolean => {
        const initialLength = recipes.value.length;
        recipes.value = recipes.value.filter(recipe => recipe.id !== id);
        return recipes.value.length !== initialLength;
    };

    return {
        recipes,
        addRecipe,
        getRecipeById,
        getRecipesByTitle,
        deleteRecipe,
    };
};