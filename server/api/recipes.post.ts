// server/api/recipes.post.ts
import { defineEventHandler, readBody } from 'h3'
import type { Recipe, Difficulty as AppDifficulty } from '~/shared/types/recipe.types'
import { Difficulty } from '../../app/generated/prisma/enums'

// Маппинг строковых значений сложности в Prisma enum
const difficultyMap: Record<AppDifficulty, Difficulty> = {
  'легко': Difficulty.LOW,
  'средне': Difficulty.MEDIUM,
  'сложно': Difficulty.HIGH,
}

export default defineEventHandler(async (event) => {
  try {
    // 1. Читаем данные, которые прислала форма
    const body = await readBody<Omit<Recipe, 'id'>>(event)
    
    // 2. Сохраняем в базу данных через Prisma
    const newRecipe = await prisma.recipe.create({
      data: {
        title: body.title,
        description: body.description,
        cookingTime: body.cookingTime,
        servings: body.servings,
        difficulty: difficultyMap[body.difficulty],
        imageUrl: body.imageUrl,
        steps: body.steps,
        // Prisma позволяет очень красиво сохранить связанные таблицы (ингредиенты) в один запрос
        ingredients: {
          create: body.ingredients.map(ing => ({
            name: ing.name,
            quantity: ing.quantity,
            unit: ing.unit
          }))
        }
      },
      // Сразу просим базу вернуть нам созданный рецепт вместе с ингредиентами
      include: {
        ingredients: true
      }
    })

    // 3. Возвращаем успешный ответ фронтенду
    return {
      success: true,
      data: newRecipe
    }
  } catch (error) {
    // Если что-то пошло не так (например, база упала), сервер не зависнет, а отдаст ошибку 500
    console.error('Ошибка сохранения в БД:', error)
    throw createError({
      statusCode: 500,
      statusMessage: 'Внутренняя ошибка сервера при сохранении рецепта'
    })
  }
})