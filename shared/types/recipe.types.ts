// Единицы измерения для ингредиентов
export type Unit = 'г' | 'кг' | 'мл' | 'л' | 'шт' | 'ст.л.' | 'ч.л.' | 'по вкусу';

// Уровень сложности рецепта
export type Difficulty = 'легко' | 'средне' | 'сложно';

// Интерфейс для одного ингредиента
export interface Ingredient {
  name: string;
  quantity: number;
  unit: Unit;
}

// Основной интерфейс для рецепта
export interface Recipe {
  id: number;
  title: string;
  description: string;
  cookingTime: number; // в минутах
  servings: number; // количество порций
  difficulty: Difficulty;
  ingredients: Ingredient[];
  steps: string[]; // Массив строк с шагами приготовления
  imageUrl?: string | null; // Ссылка на изображение (можно использовать локальные файлы из /public)
}