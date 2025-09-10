# Vue TypeScript Cookbook

Современный шаблон проекта с **Nuxt 4**, **Vue 3 Composition API**, **TypeScript** и **Tailwind CSS**.

## 🚀 Технологический стек

- **Framework:** Nuxt 4.1.1
- **Frontend:** Vue 3.5.21 с Composition API
- **Language:** TypeScript 5.5.4
- **Styling:** Tailwind CSS 3.4.17
- **Package Manager:** pnpm 9+
- **Build Tool:** Vite (встроен в Nuxt 4)
- **Node.js:** 20.19.5+

## 📋 Пошаговая инструкция создания проекта

### 1. Подготовка окружения

```bash
# Убедитесь, что у вас установлен Node.js 20+
node --version  # должно быть >= 20.19.5

# Установите pnpm глобально (если не установлен)
npm install -g pnpm

# Переключитесь на правильную версию Node.js через nvm (если используете)
nvm use 20.19.5
```

### 2. Создание проекта

```bash
# Создайте новую директорию проекта
mkdir vue-ts-cbook
cd vue-ts-cbook

# Инициализируйте пустой проект
pnpm init
```

### 3. Настройка package.json

Создайте или обновите `package.json`:

```json
{
  "name": "vue-ts-cbook",
  "version": "1.0.0",
  "type": "module",
  "private": true,
  "description": "Modern Vue.js boilerplate with Nuxt 4, TypeScript, Tailwind CSS",
  "scripts": {
    "build": "nuxt build",
    "dev": "nuxt dev",
    "generate": "nuxt generate",
    "preview": "nuxt preview",
    "postinstall": "nuxt prepare",
    "typecheck": "nuxt typecheck"
  },
  "packageManager": "pnpm@9.0.0",
  "dependencies": {
    "@vueuse/core": "^13.9.0",
    "@vueuse/nuxt": "^13.9.0",
    "nuxt": "^4.1.1",
    "vue": "^3.5.21",
    "vue-router": "^4.5.1"
  },
  "devDependencies": {
    "@nuxtjs/tailwindcss": "^6.14.0",
    "autoprefixer": "^10.4.21",
    "postcss": "^8.4.47",
    "tailwindcss": "^3.4.14",
    "typescript": "^5.5.4"
  }
}
```

### 4. Установка зависимостей

```bash
# Установите все зависимости
pnpm install
```

### 5. Создание структуры директорий

```bash
# Создайте необходимые директории
mkdir -p app
mkdir -p assets/css
mkdir -p components
mkdir -p layouts
mkdir -p pages
mkdir -p public
mkdir -p server
mkdir -p utils
```

### 6. Конфигурация Nuxt

Создайте `nuxt.config.ts`:

```typescript
// https://nuxt.com/docs/api/configuration/nuxt-config
export default defineNuxtConfig({
  compatibilityDate: '2024-04-03',
  devtools: { enabled: true },
  
  // Modules
  modules: [
    '@nuxtjs/tailwindcss'
  ]
})
```

### 7. Конфигурация TypeScript

Создайте `tsconfig.json`:

```json
{
  "extends": "./.nuxt/tsconfig.json"
}
```

### 8. Настройка Tailwind CSS

Создайте `tailwind.config.js`:

```javascript
/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./components/**/*.{js,vue,ts}",
    "./layouts/**/*.vue",
    "./pages/**/*.vue",
    "./plugins/**/*.{js,ts}",
    "./app.vue",
    "./error.vue"
  ],
  theme: {
    extend: {},
  },
  plugins: [],
}
```

Создайте `assets/css/main.css`:

```css
@tailwind base;
@tailwind components;
@tailwind utilities;
```

### 9. Создание главного компонента

Создайте `app/app.vue` с демонстрационным интерфейсом, показывающим работу всех технологий.

### 10. Создание .gitignore

```gitignore
# Nuxt dev/build outputs
.output
.data
.nuxt
.nitro
.cache
dist

# Node dependencies
node_modules

# Logs
*.log

# Misc
.DS_Store
.fleet
.idea

# Local env files
.env
.env.*
!.env.example
```

## 🚀 Запуск проекта

```bash
# Запуск в режиме разработки
pnpm dev

# Сборка для продакшена
pnpm build

# Предварительный просмотр продакшен сборки
pnpm preview

# Проверка типов TypeScript
pnpm typecheck
```

## 📁 Структура проекта

```
vue-ts-cbook/
├── app/
│   └── app.vue              # Главный компонент приложения
├── assets/
│   └── css/
│       └── main.css         # Основные стили Tailwind CSS
├── components/              # Vue компоненты
├── layouts/                 # Макеты страниц
├── pages/                   # Страницы (файловая маршрутизация)
├── public/                  # Статические файлы
├── server/                  # Серверные API маршруты
├── utils/                   # Утилиты и хелперы
├── nuxt.config.ts          # Конфигурация Nuxt
├── tailwind.config.js      # Конфигурация Tailwind CSS
├── tsconfig.json           # Конфигурация TypeScript
├── package.json            # Зависимости и скрипты
└── README.md              # Документация проекта
```

## 🔧 Важные моменты при настройке

### Совместимость версий

- **Node.js:** Обязательно используйте версию 20.19.5 или выше
- **PostCSS:** Версия 8.4.47+ для совместимости с Tailwind CSS 3.x
- **Tailwind CSS:** Используйте версию 3.4.x (не 4.x) для стабильности с Nuxt 4

### Возможные проблемы и решения

1. **Ошибка модуля CSS:** Убедитесь, что используете модуль `@nuxtjs/tailwindcss`
2. **Конфликт PostCSS:** Обновите PostCSS до последней версии 8.4.x
3. **Проблемы с типами:** Запустите `pnpm postinstall` для генерации типов Nuxt

## 📚 Примеры использования

### TypeScript с Vue 3 Composition API

```typescript
<script setup lang="ts">
// Reactive state with TypeScript
const counter = ref<number>(0)
const userInput = ref<string>('')

// Computed properties with TypeScript
const characterCount = computed<number>(() => userInput.value.length)

// Methods with TypeScript annotations
const increment = (): void => {
  counter.value++
}

// Lifecycle hooks
onMounted(() => {
  console.log('Component mounted!')
})

// SEO Meta
useSeoMeta({
  title: 'Vue TypeScript Cookbook',
  description: 'Modern Vue.js boilerplate'
})
</script>
```

### Tailwind CSS классы

```vue
<template>
  <div class="min-h-screen bg-slate-50 p-8">
    <div class="max-w-4xl mx-auto">
      <button class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition-colors">
        Click me
      </button>
    </div>
  </div>
</template>
```

## 🎯 Следующие шаги

1. Создайте собственные UI компоненты в папке `components/`
2. Добавьте страницы в папку `pages/` для файловой маршрутизации
3. Настройте серверные API в папке `server/api/`
4. Добавьте middleware для аутентификации и авторизации
5. Настройте SEO и мета-теги для каждой страницы

## 📚 Полезные ссылки

- [Nuxt 4 Documentation](https://nuxt.com/)
- [Vue 3 Composition API](https://vuejs.org/guide/extras/composition-api-faq.html)
- [TypeScript Handbook](https://www.typescriptlang.org/docs/)
- [Tailwind CSS Documentation](https://tailwindcss.com/docs)
- [VueUse Composables](https://vueuse.org/)

## 📄 Лицензия

MIT License
