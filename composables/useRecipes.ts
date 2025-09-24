import { ref, computed, onMounted } from 'vue';
import type { Recipe } from '../types';
import { mockRecipes } from '../data/mockRecipes';

const STORAGE_KEY = 'recipe-app-recipes';

export const useRecipes = () => {
    // Load recipes from localStorage or use mock data
    const loadRecipes = (): Recipe[] => {
        try {
            const saved = localStorage.getItem(STORAGE_KEY);
            return saved ? JSON.parse(saved) : [...mockRecipes];
        } catch (e) {
            console.error('Failed to load recipes:', e);
            return [...mockRecipes];
        }
    };

    // Reactive state
    const recipes = ref<Recipe[]>([]);

    // Initialize from localStorage
    onMounted(() => {
        recipes.value = loadRecipes();
    });

    // Save recipes to localStorage
    const saveRecipes = () => {
        try {
            localStorage.setItem(STORAGE_KEY, JSON.stringify(recipes.value));
        } catch (e) {
            console.error('Failed to save recipes:', e);
        }
    };

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

        recipes.value.push(recipe);
        saveRecipes();
        return recipe;
    };

    // Get all recipes (computed for reactivity)
    const getAllRecipes = computed(() => [...recipes.value]);

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
        if (recipes.value.length !== initialLength) {
            saveRecipes();
            return true;
        }
        return false;
    };

    return {
        recipes,
        getAllRecipes,
        addRecipe,
        getRecipeById,
        getRecipesByTitle,
        deleteRecipe,
    };
};