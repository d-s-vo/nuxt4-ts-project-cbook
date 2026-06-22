// server/api/recipes.post.ts
import { defineEventHandler, createError, readMultipartFormData } from 'h3'
import { prisma } from '../utils/prisma'
import { Difficulty } from '#prisma'
import { S3Client, PutObjectCommand } from '@aws-sdk/client-s3'

// 1. НАСТРОЙКА КЛИЕНТА S3
const s3 = new S3Client({
  region: process.env.S3_REGION || 'us-east-1',
  endpoint: process.env.S3_ENDPOINT,
  forcePathStyle: true, // КРИТИЧЕСКИ ВАЖНО ДЛЯ MINIO! Без этого SDK попытается подставить имя бакета в домен.
  credentials: {
    accessKeyId: process.env.S3_ACCESS_KEY!,
    secretAccessKey: process.env.S3_SECRET_KEY!
  }
})

const difficultyMap: Record<string, Difficulty> = {
  'легко': Difficulty.LOW,
  'средне': Difficulty.MEDIUM,
  'сложно': Difficulty.HIGH
}

const ALLOWED_MIME_TYPES = ['image/jpeg', 'image/webp', 'image/png', 'image/avif']

export default defineEventHandler(async (event) => {
  try {
    const parts = await readMultipartFormData(event)
    
    if (!parts) {
      throw createError({ statusCode: 400, statusMessage: 'Нет данных в запросе' })
    }

    const fields: Record<string, any> = {}
    let fileBuffer: Buffer | null = null
    let fileName: string | null = null
    let fileMimeType: string | null = null

    // 2. РАЗБИРАЕМ ЗАПРОС
    for (const part of parts) {
      if ((part.name === 'image' || part.name === 'imageFile') && part.filename) {
        
        if (!ALLOWED_MIME_TYPES.includes(part.type as string)) {
          throw createError({ 
            statusCode: 400, 
            statusMessage: 'Недопустимый формат файла. Разрешены только JPG, WEBP, PNG, AVIF.' 
          })
        }
        
        fileBuffer = part.data
        fileName = part.filename
        fileMimeType = part.type || 'image/jpeg'
      } else if (part.name) {
        fields[part.name] = part.data.toString('utf-8')
      }
    }

    let finalImageUrl = fields.imageUrl || null 

    // 3. ОТПРАВЛЯЕМ ФАЙЛ В S3 (MINIO) ВМЕСТО ЛОКАЛЬНОГО ДИСКА
    if (fileBuffer && fileName) {
      // Формируем уникальный путь внутри бакета (папка recipes/)
      const uniqueFileName = `recipes/${Date.now()}-${fileName}`

      try {
        await s3.send(new PutObjectCommand({
          Bucket: process.env.S3_BUCKET,
          Key: uniqueFileName,
          Body: fileBuffer,
          ContentType: fileMimeType!,
          ACL: 'public-read'
        }))
        
        // Формируем готовую ссылку через Ngrok
        finalImageUrl = `${process.env.S3_ENDPOINT}/${process.env.S3_BUCKET}/${uniqueFileName}`
      } catch (err) {
        const s3Error = err as Error;
        console.error('Ошибка загрузки в S3:', s3Error)
        // ПРОБРАСЫВАЕМ ОШИБКУ S3 ПРЯМО В PLAYWRIGHT
        throw createError({ 
          statusCode: 500, 
          statusMessage: 'Не удалось загрузить изображение в облако',
          data: { 
            realS3Error: s3Error.message, 
            s3ErrorCode: s3Error.name,
            bucketName: process.env.S3_BUCKET || 'ПУСТО!' // Заодно проверим переменную!
          }
        })
      }
    }

    // 4. РАСПАКОВКА МАССИВОВ
    const parsedIngredients = fields.ingredients ? JSON.parse(fields.ingredients) : []
    const parsedSteps = fields.steps ? JSON.parse(fields.steps) : []

    // 5. СОХРАНЯЕМ В БАЗУ ДАННЫХ
    const newRecipe = await prisma.recipe.create({
      data: {
        title: fields.title,
        description: fields.description,
        cookingTime: Number(fields.cookingTime),
        servings: Number(fields.servings),
        difficulty: difficultyMap[fields.difficulty] || Difficulty.LOW,
        imageUrl: finalImageUrl,
        steps: parsedSteps,
        ingredients: {
          create: parsedIngredients.map((ing: any) => ({
            name: ing.name,
            quantity: Number(ing.quantity), // Не забываем приводить к числу!
            unit: ing.unit
          }))
        }
      },
      include: {
        ingredients: true
      }
    })

    return {
      success: true,
      data: newRecipe
    }
  } catch (error) {
    console.error('Ошибка сохранения в БД:', error)
    if (error && typeof error === 'object' && 'statusCode' in error) throw error;
    
    const message = error instanceof Error ? error.message : String(error)
    throw createError({
      statusCode: 500,
      statusMessage: 'Внутренняя ошибка сервера при сохранении рецепта',
      data: { detail: message }
    })
  }
})