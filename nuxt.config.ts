import { defineNuxtConfig } from 'nuxt/config'

// https://nuxt.com/docs/api/configuration/nuxt-config
export default defineNuxtConfig({
  typescript: {
    typeCheck: true
  },
  compatibilityDate: '2024-04-03',
  devtools: { enabled: true },
  dev: true,
  vite: {
    server: {
      hmr: true
    }
  },
  css: [
		'@/assets/css/main.css'
	],
  // Modules
  modules: [
    '@nuxtjs/tailwindcss'
  ],
  // Ensure pages directory is properly configured
  srcDir: '.',
  app: {
    pageTransition: { name: 'page', mode: 'out-in' }
  }
})
