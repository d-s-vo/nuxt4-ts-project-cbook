import { defineEventHandler, createError, getRouterParam } from 'h3'
import { prisma } from '~~/server/utils/prisma'

export default defineEventHandler(async (event) => {
  const id = Number(getRouterParam(event, 'id'))

  if (isNaN(id)) {
    throw createError({ statusCode: 400, statusMessage: 'Неверный ID' })
  }

  try {
    // Удаляем рецепт из базы
    await prisma.recipe.delete({
      where: { id: id }
    })
    
    return { success: true }
  } catch (error) {
    throw createError({ statusCode: 500, statusMessage: 'Ошибка при удалении' })
  }
})