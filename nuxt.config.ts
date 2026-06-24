import { defineNuxtConfig } from 'nuxt/config'

// https://nuxt.com/docs/api/configuration/nuxt-config
export default defineNuxtConfig({
  runtimeConfig: {
    public: {
      siteUrl: 'https://nuxt4-ts-project-cbook-b57b.vercel.app',
    }
  },
  future: {
    compatibilityVersion: 4,
  },
  typescript: {
    typeCheck: true
  },
  compatibilityDate: '2024-04-03',
  devtools: { enabled: true },
  nitro: {
    typescript: {
      tsConfig: {
        include: ['../prisma/generated/client/**/*']
      }
    }
  },
  vite: {
    server: {
      hmr: true,
      watch: {
        // Заставляем Vite игнорировать любые изменения в папке minio_data
        ignored: ['**/minio_data/**'] 
      }
    },
    optimizeDeps: {
      include: [
        '@vue/devtools-core',
        '@vue/devtools-kit',
        '@vueuse/core',
        'zod'
      ]
    }
  },
  css: ['./app/assets/css/main.css'],
  modules: ['@nuxt/ui', '@nuxtjs/device'],
  app: {
    pageTransition: { name: 'page', mode: 'out-in' }
  },
  plugins: [
    '~/plugins/bvi-speech-tracker.client.ts'
  ]
})
