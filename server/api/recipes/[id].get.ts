import { defineEventHandler, createError, getRouterParam } from 'h3'
import { prisma } from '~~/server/utils/prisma' // Наш настроенный клиент БД

export default defineEventHandler(async (event) => {
  // Вытаскиваем ID из URL (например, запрос на /api/recipes/5 даст нам id = 5)
  const id = Number(getRouterParam(event, 'id'))

  if (isNaN(id)) {
    throw createError({ statusCode: 400, statusMessage: 'Неверный ID рецепта' })
  }

  // Ищем рецепт в базе данных
  const recipe = await prisma.recipe.findUnique({
    where: { id: id },
    include: { ingredients: true } // Обязательно подтягиваем ингредиенты
  })

  if (!recipe) {
    throw createError({ statusCode: 404, statusMessage: 'Рецепт не найден' })
  }

  return recipe
})