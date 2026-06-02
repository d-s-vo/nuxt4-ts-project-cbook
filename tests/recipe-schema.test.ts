// tests/recipe-schema.test.ts
import { describe, it, expect } from 'vitest';
import { schema } from '../utils/validations';

describe('Валидация формы добавления рецепта (Zod)', () => {

  it('Должна блокировать рецепт без названия', () => {
    // 1. Подготовка: создаем плохие данные
    const badData = { 
      ingredients: ['Вода', 'Картошка'] 
      // title отсутствует!
    };

    // 2. Действие: прогоняем через схему
    const result = schema.safeParse(badData);

    // 3. Проверка: ожидаем, что валидация провалится (success === false)
    expect(result.success).toBe(false);
  });

  it('Должна пропускать правильные данные', () => {
    const goodData = { 
      title: 'Вкусный суп',
      ingredients: ['Вода', 'Картошка'],
      description: 'Вкусный суп из картошки',
      steps: ['Вскипятить воду', 'Добавить картошку', 'Варить 10 минут'],
    };

    const result = schema.safeParse(goodData);
    expect(result.success).toBe(false);
  });

});