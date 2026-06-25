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
    pageTransition: { name: 'page', mode: 'out-in' },
    head: {
			viewport: 'width=device-width, initial-scale=1, minimum-scale=1.0',
			link: [
				// favicons
				{ rel: 'icon', type: 'image/png', sizes: '96x96', href: '/favicon-96x96.png' },
				{ rel: 'icon', type: 'image/svg+xml', href: '/favicon.svg' },
				{ rel: 'shortcut icon', href: '/favicon.ico' },
				{ rel: 'apple-touch-icon', sizes: '180x180', href: '/apple-touch-icon.png' },
				{ rel: 'manifest', href: '/site.webmanifest' }
			],
			htmlAttrs: {
				lang: 'ru'
			},
			
			meta: [
				// favicons
				{ name: 'msapplication-TileColor', content: '#ffffff' },
				{ name: 'theme-color', content: '#ffffff' },
				{ name: 'apple-mobile-web-app-title', content: 'CookBook' }
			]
		}
  },
  plugins: [
    '~/plugins/bvi-speech-tracker.client.ts'
  ]
})