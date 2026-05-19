// server/api/recipes.get.ts
import { defineEventHandler, createError } from 'h3'
// Импортируем наш настроенный инстанс клиента
import { prisma } from '../utils/prisma'

export default defineEventHandler(async () => {
  try {
    // Просим Prisma достать все рецепты из таблицы Recipe
    const recipes = await prisma.recipe.findMany({
      // Сразу подтягиваем связанные ингредиенты
      include: {
        ingredients: true
      },
      // Сортируем так, чтобы свежие рецепты были сверху
      orderBy: {
        createdAt: 'desc'
      }
    })
    
    return recipes

  } catch (error) {
    console.error('Ошибка при получении рецептов из БД:', error)
    
    throw createError({
      statusCode: 500,
      statusMessage: 'Не удалось загрузить рецепты'
    })
  }
})