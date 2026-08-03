declare namespace App {
namespace Data {
export type IngredientData = {
id: number,
name: string,
quantity: number,
unit: string,
};
export type RecipeData = {
id: number,
title: string,
description: string,
cooking_time: number,
servings: number,
difficulty: App.Enums.Difficulty,
steps: string[],
ingredients: App.Data.IngredientData[],
created_at: string | null,
updated_at: string | null,
};
export type UserData = {
id: number,
name: string,
email: string,
email_verified_at: string | null,
};
}
namespace Enums {
export type Difficulty = 'low' | 'medium' | 'high';
}
}
