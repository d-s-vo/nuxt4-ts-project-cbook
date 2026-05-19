// server/api/recipes.post.ts
import { defineEventHandler, readBody } from 'h3'
import type { Recipe } from '~/shared/types/recipe.types'

export default defineEventHandler(async (event) => {
  // Читаем тело POST-запроса. 
  // Omit означает, что мы ждем всё из Recipe, кроме поля 'id'
  const body = await readBody<Omit<Recipe, 'id'>>(event)
  
  // Здесь в будущем будет вызов ORM: await db.insert(recipesTable).values(body)
  
  // А пока просто имитируем, что сохранили в базу и присвоили ID
  const newRecipe: Recipe = {
    id: Date.now(), 
    ...body
  }

  // Возвращаем успешный ответ
  return {
    success: true,
    message: 'Рецепт успешно добавлен в мок-базу',
    data: newRecipe
  }
})