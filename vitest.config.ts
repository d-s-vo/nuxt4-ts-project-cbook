// vitest.config.ts
import { defineConfig } from 'vitest/config';

export default defineConfig({
  test: {
    // Включаем поддержку глобальных функций (describe, it, expect)
    globals: true, 
    // Говорим Vitest'у, где искать файлы с тестами
    include: ['tests/**/*.test.ts'], 
  },
});