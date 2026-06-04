import * as z from 'zod';

// Обработка файлов
const MAX_FILE_SIZE = 5 * 1024 * 1024; // 5 МБ в байтах
const ACCEPTED_IMAGE_TYPES = ['image/jpeg', 'image/png', 'image/webp', 'image/avif'];
const units: Unit[] = ['г', 'кг', 'мл', 'л', 'шт', 'ст.л.', 'ч.л.', 'по вкусу'] as const;

export const schema = z.object({
  title: z.string().min(1, 'Название блюда обязательно'),
  description: z.string().min(1, 'Описание блюда обязательно'),
  cookingTime: z.number().min(1, 'Время приготовления обязательно'),
  servings: z.number().min(1, 'Количество порций обязательно'),
  difficulty: z.enum(['легко', 'средне', 'сложно'], { message: 'Сложность обязательно'}),
  ingredients: z.array(z.object({
    name: z.string().min(1, 'Название ингредиента обязательно'),
    quantity: z.number().min(1, 'Количество обязательно'),
    unit: z.enum(units, { message: 'Единица измерения обязательно'})
  })),
  steps: z.array(z.string().min(1, 'Шаг обязательно')),
  imageFile: z.any()
    // Проверяем, что если значение есть, то это именно объект File
    .refine((file) => !file || file instanceof File, 'Ожидается файл изображения')
    // Проверяем размер
    .refine((file) => !file || file.size <= MAX_FILE_SIZE, 'Размер файла не должен превышать 5 МБ')
    // Проверяем MIME-тип
    .refine(
      (file) => !file || ACCEPTED_IMAGE_TYPES.includes(file.type), 
      'Поддерживаются только форматы .jpg, .png и .webp'
    )
});