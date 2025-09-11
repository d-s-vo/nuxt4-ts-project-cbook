// https://nuxt.com/docs/api/configuration/nuxt-config
export default defineNuxtConfig({
  compatibilityDate: '2024-04-03',
  devtools: { enabled: true },
  dev: true,
  vite: {
    server: {
      hmr: true
    }
  },
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
