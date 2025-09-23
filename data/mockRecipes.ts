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
        imageUrl: "/images/applepie.jpg",
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
        imageUrl: "/images/mannik.jpg",
        servings: 1,
    },
    {
        id: 3,
        title: "Классические молочные блины",
        description: "Тонкие, нежные и ароматные блины на молоке — идеальный завтрак для всей семьи. Отлично сочетаются со сметаной, вареньем или медом.",
        cookingTime: 30,
        servings: 4,
        difficulty: "легко",
        ingredients: [
            { 
                name: "Молоко", 
                quantity: 500, 
                unit: "мл" 
            },
            { 
                name: "Яйца",
                quantity: 3,
                unit: "шт" 
            },
            { 
                name: "Мука пшеничная",
                quantity: 200, 
                unit: "г" 
            },
            { 
                name: "Сахар",
                quantity: 2, 
                unit: "ст.л." 
            },
            { 
                name: "Соль",
                quantity: 0.5,
                unit: "ч.л." 
            },
            { 
                name: "Масло растительное",
                quantity: 2, 
                unit: "ст.л." 
            },
            { 
                name: "Масло сливочное",
                quantity: 30,
                unit: "г" 
            }
        ],
        steps: [
            "В глубокой миске взбейте яйца с сахаром и солью до легкой пены",
            "Добавьте половину молока и постепенно введите муку, тщательно размешивая чтобы не было комочков",
            "Влейте оставшееся молоко, перемешайте до однородной консистенции",
            "Добавьте растительное масло в тесто и дайте постоять 15-20 минут",
            "Разогрейте сковороду на среднем огне и слегка смажьте сливочным маслом",
            "Налейте половник теста, распределите по сковороде тонким слоем",
            "Жарьте 1-2 минуты до золотистого цвета, затем переверните и жарьте еще минуту",
            "Готовые блины складывайте стопкой, при желании смазывая каждый сливочным маслом"
        ],
        imageUrl: "/images/pancakes.jpg"
    }
]

export const getRecipeById = (id: number): Recipe | undefined => {
    return mockRecipes.find(recipe => recipe.id === id)
};

export const getAllRecipes = (): Recipe[] => {
    return [...mockRecipes];
};