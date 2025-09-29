import type { Recipe, Ingredient } from './recipe.types';

// --- ключи главной секции (без вложенности)
export type MainKeys = keyof Omit<Recipe, 'id' | 'ingredients' | 'steps'>;

// --- ключи ингредиентов
export type IngredientKeys = keyof Ingredient;

// --- шаги (массив строк)
export type StepKeys = 'step';

// --- Общие поля формы ---
interface BaseField<TName extends string = string> {
  type: 'text' | 'number' | 'textarea' | 'select';
  name: TName;
  title: string;
  required?: boolean;
}

export interface SelectField<TName extends string = string> extends BaseField<TName> {
  type: 'select';
  options: { value: string; title: string }[];
}

export type Field<TName extends string = string> = BaseField<TName> | SelectField<TName>;

// --- Группы ---
export interface MainGroup {
  title: string;
  type: 'main';
  items: Field<MainKeys>[];
}

export interface IngredientsGroup {
  title: string;
  type: 'ingredients';
  items: Field<IngredientKeys>[];
}

export interface StepsGroup {
  title: string;
  type: 'steps';
  items: Field<StepKeys>[];
}

// --- Общий тип ---
export type FormGroup = MainGroup | IngredientsGroup | StepsGroup;
