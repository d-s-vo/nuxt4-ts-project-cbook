import { defineNuxtConfig } from 'nuxt/config'

// https://nuxt.com/docs/api/configuration/nuxt-config
export default defineNuxtConfig({
  future: {
    compatibilityVersion: 4,
  },
  typescript: {
    typeCheck: false
  },
  compatibilityDate: '2024-04-03',
  devtools: { enabled: true },
  dev: true,
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
  }
})
