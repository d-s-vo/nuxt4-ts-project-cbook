import { PrismaClient } from '../../prisma/generated/client/client'
import { PrismaPg } from '@prisma/adapter-pg'

// Мы создаем единственный экземпляр клиента, 
// чтобы не перегружать базу новыми подключениями при каждом запросе
const adapter = new PrismaPg({ connectionString: process.env.DATABASE_URL })
export const prisma = new PrismaClient({ adapter })