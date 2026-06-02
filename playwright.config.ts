import { defineConfig } from '@playwright/test';

export default defineConfig({
  testDir: './tests/e2e',
  timeout: 60_000,
  use: {
    baseURL: 'http://localhost:3000',
  },
  webServer: {
    // Запускаем Nuxt с тестовым .env
    command: 'pnpm nuxt dev --dotenv .env.test',
    port: 3000,
    reuseExistingServer: !process.env.CI,
    timeout: 120_000,
    env: {
      ...process.env,
      S3_BUCKET: process.env.S3_BUCKET || 'recipes',
    },
  },
});
