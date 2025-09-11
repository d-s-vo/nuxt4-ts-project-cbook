import type { Recipe } from "../types/index.d.ts";

export const mockRecipes: Recipe[] = [
    {
        id: 1,
        title: "Шарлотка",
        description: "Бисквитный пиорог с яблоками",
        cookingTime: 120,
        difficulty: "легко",
        ingredients: [
            {
                name: "мука",
                quantity: 500,
                unit: "г"
            },
            {
                name: "яйца",
                quantity: 3,
                unit: "шт"
            },
            {
                name: "сахар",
                quantity: 200,
                unit: "г"
            },
            {
                name: "яблоки",
                quantity: 4,
                unit: "шт"
            },
        ],
        steps: [
            "Яйца с сахаром взбить в пышную пену",
            "Добавить муку, аккуратно перемешать",
            "Яблоки порезать кубиками, добавить в тесто",
            "Вылить в смазанную форму",
            "Выпекать 25-30 минут при 180°C"
        ],
        imageUrl: "",
        servings: 1,
    },
    {
        id: 2,
        title: "Манник",
        description: "Сладкий пирог из манки и кефира",
        cookingTime: 120,
        difficulty: "легко",
        ingredients: [
            {
                name: "манка",
                quantity: 250,
                unit: "г"
            },
            {
                name: "яйца",
                quantity: 2,
                unit: "шт"
            },
            {
                name: "сахар",
                quantity: 200,
                unit: "г"
            },
            {
                name: "кефир",
                quantity: 250,
                unit: "мл"
            },
            {
                name: "разрыхлитель",
                quantity: 1,
                unit: "ч.л."
            },
        ],
        steps: [
            "Манку залить кефиром, оставить на 30 минут",
            "Добавить яйца, сахар, муку и соду",
            "Перемешать, вылить в форму",
            "Выпекать 35-40 минут при 180°C",
            "Можно добавить ягоды или яблоки"
        ],
        imageUrl: "",
        servings: 1,
    }
]

export const getRecipeById = (id: number): Recipe | undefined => {
    return mockRecipes.find(recipe => recipe.id === id)
};

export const getAllRecipes = (): Recipe[] => {
    return [...mockRecipes];
};