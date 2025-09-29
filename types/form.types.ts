import type { Recipe } from './index.d.ts';

export type RecipeFormKeys = keyof Recipe;

interface BaseField<T extends RecipeFormKeys = RecipeFormKeys> {
  type: 'text' | 'number' | 'textarea';
  name: T;
  title: string;
  required?: boolean;
}

export interface SelectField<T extends RecipeFormKeys = RecipeFormKeys> extends BaseField<T> {
  type: 'select';
  options: { value: string; title: string }[];
}

export type Field = BaseField | SelectField;

export interface FormGroup {
  title: string;
  type: 'main' | 'ingredients' | 'steps';
  items: Field[];
}
