// server/api/recipes/[id].delete.ts
import { defineEventHandler, createError, getRouterParam } from 'h3'
import { prisma } from '~~/server/utils/prisma'
import { S3Client, DeleteObjectCommand } from '@aws-sdk/client-s3'

// 1. Инициализируем клиент S3 точно так же, как при загрузке
const s3 = new S3Client({
  region: process.env.S3_REGION || 'us-east-1',
  endpoint: process.env.S3_ENDPOINT,
  forcePathStyle: true, // Критично для MinIO!
  credentials: {
    accessKeyId: process.env.S3_ACCESS_KEY!,
    secretAccessKey: process.env.S3_SECRET_KEY!
  }
})

export default defineEventHandler(async (event) => {
  try {
    const id = getRouterParam(event, 'id')
    const recipeId = Number(id)

    if (!id || isNaN(recipeId)) {
      throw createError({ statusCode: 400, statusMessage: 'Неверный ID рецепта' })
    }

    // 2. Сначала НАХОДИМ рецепт, чтобы забрать ссылку на картинку
    const recipe = await prisma.recipe.findUnique({
      where: { id: recipeId },
      select: { imageUrl: true }
    })

    if (!recipe) {
      throw createError({ statusCode: 404, statusMessage: 'Рецепт не найден' })
    }

    // 3. УДАЛЯЕМ ФАЙЛ ИЗ MINIO (если он есть)
    if (recipe.imageUrl) {
      try {
        const urlObj = new URL(recipe.imageUrl)
        
        // urlObj.pathname вернет: /recipes/recipes/177...-image.avif
        // Нам нужно вырезать первый /recipes/ (имя бакета), чтобы остался только ключ
        const bucketPrefix = `/${process.env.S3_BUCKET}/`
        
        // Заменяем префикс бакета на пустую строку и декодируем URL 
        // (на случай, если в имени файла были пробелы или русские буквы)
        const objectKey = decodeURIComponent(urlObj.pathname.replace(bucketPrefix, ''))

        await s3.send(new DeleteObjectCommand({
          Bucket: process.env.S3_BUCKET,
          Key: objectKey
        }))
        
        console.log(`✅ Файл ${objectKey} успешно удален из S3 (MinIO)`)
      } catch (s3Error) {
        console.error('⚠️ Ошибка удаления файла из S3:', s3Error)
      }
    }

    // 4. УДАЛЯЕМ РЕЦЕПТ ИЗ POSTGRESQL
    // Каскадное удаление (onDelete: Cascade в Prisma) удалит и связанные ингредиенты
    await prisma.recipe.delete({
      where: { id: recipeId }
    })

    return { 
      success: true, 
      message: 'Рецепт и его изображение успешно удалены' 
    }

  } catch (error) {
    console.error('Ошибка при удалении рецепта:', error)
    if (error && typeof error === 'object' && 'statusCode' in error) throw error
    throw createError({ statusCode: 500, statusMessage: 'Внутренняя ошибка сервера' })
  }
})