<template>
  <div class="min-h-screen font-sans">
      <!-- Панель для слабовидящих -->
      <BviPanel />
      
      <!-- Кнопка активации BVI режима (справа внизу под шапкой) -->
      <button
        v-if="!isEnabled"
        @click="toggleBvi"
        aria-label="Включить версию для слабовидящих"
        class="fixed bottom-4 right-4 z-[9998] w-14 h-14 bg-blue-600 hover:bg-blue-700 text-white rounded-full shadow-lg flex items-center justify-center focus:ring-4 focus:ring-blue-300 focus:outline-none transition-all hover:scale-105"
        title="Версия для слабовидящих"
      >
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
          <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
        </svg>
      </button>
      
      <NuxtLayout :name="isMobileOrTablet ? 'mobile' : 'desktop'">
        <NuxtPage />
      </NuxtLayout>
  </div>
</template>

<script setup lang="ts">
const { isMobileOrTablet } = useDevice();

const config = useRuntimeConfig()

useSeoMeta({
  // Базовое SEO
  titleTemplate: '%s | CBook — Кулинарная книга',
  title: 'Главная',
  description: 'Проверенные пошаговые рецепты',

  // OpenGraph (VK, Telegram, WhatsApp)
  ogType: 'website',
  ogSiteName: 'CBook',
  ogTitle: 'CBook — Современная кулинарная книга',
  ogDescription: 'Проверенные пошаговые рецепты',
  ogImage: `${config.public.siteUrl}/og-default.jpg`,
  ogUrl: config.public.siteUrl,
  ogLocale: 'ru_RU',

  // Twitter / X (отвечает за широкие превью в мессенджерах)
  twitterCard: 'summary_large_image',
  twitterTitle: 'CBook — Современная кулинарная книга',
  twitterDescription: 'Проверенные пошаговые рецепты',
  twitterImage: `${config.public.siteUrl}/og-default.jpg`,
})

// Получаем состояние BVI из composable
const { isEnabled, fontSize, theme, spacing, imageMode, toggleBvi } = useBvi()

/**
 * Вычисляемые классы для body на основе BVI настроек
 * Добавляет классы тем, интервалов и изображений
 */
const bviBodyClasses = computed(() => {
  if (!isEnabled.value) return ''
  
  const classes = []
  
  // Класс темы
  if (theme.value) {
    classes.push(`bvi-theme-${theme.value}`)
  }
  
  // Класс интервалов
  if (spacing.value && spacing.value !== 'normal') {
    classes.push(`bvi-spacing-${spacing.value}`)
  }
  
  // Класс режима изображений
  if (imageMode.value && imageMode.value !== 'default') {
    classes.push(`bvi-images-${imageMode.value}`)
  }
  
  return classes.join(' ')
})

/**
 * Вычисляемый стиль для размера шрифта body
 * Применяется только когда BVI включен
 */
const bviBodyStyle = computed(() => {
  if (!isEnabled.value) return {}
  return {
    fontSize: `${fontSize.value}px`
  }
})

/**
 * Объединяем device-классы и BVI-классы для body
 */
const bodyClasses = computed(() => {
  const deviceClass = isMobileOrTablet ? 'body_mobile' : 'body_desktop'
  const bviClasses = bviBodyClasses.value
  return bviClasses ? `${deviceClass} ${bviClasses}` : deviceClass
})

// Инициализация bodyAttrs
useHead(() => ({
  bodyAttrs: {
    class: bodyClasses.value,
    style: bviBodyStyle.value
  }
}))

// Реактивное обновление bodyAttrs при изменении BVI настроек
watch([isEnabled, fontSize, theme, spacing, imageMode], () => {
  // Прямое обновление DOM для немедленного применения изменений
  if (import.meta.client) {
    document.body.className = bodyClasses.value
    if (isEnabled.value) {
      document.body.style.fontSize = `${fontSize.value}px`
    } else {
      document.body.style.fontSize = ''
    }
  }
}, { immediate: true })
</script>