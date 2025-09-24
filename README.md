# 🍽️ Cookbook

> Современное веб-приложение для хранения и поиска кулинарных рецептов

### Предварительные требования

- **Node.js 20+**
  - Рекомендуемая версия: 20.19.5 LTS

- **pnpm 9+**
  - Менеджер пакетов для управления зависимостями
  - Установка: `npm install -g pnpm@9`
  - [Документация pnpm](https://pnpm.io/)

### Установка

1. Клонируйте репозиторий:
   ```bash
   git clone https://github.com/d-s-vo/nuxt4-ts-project-cbook
   cd *project directory*
   ```

2. Установите зависимости:
   ```bash
   pnpm install
   ```

3. Запустите сервер разработки:
   ```bash
   pnpm dev
   ```

4. Откройте [http://localhost:3000] в браузере

## Команды

```bash
# Запуск в режиме разработки
pnpm dev

# Сборка для продакшена
pnpm build

# Просмотр собранного приложения
pnpm preview

# Проверка типов TypeScript
pnpm typecheck

# Линтинг кода
pnpm lint
```

## Технический стек

- **Фреймворк:** [Nuxt 4](https://nuxt.com/)
- **Язык:** [TypeScript](https://www.typescriptlang.org/)
- **Стили:** [Tailwind CSS](https://tailwindcss.com/)
- **Состояние:** Встроенный `useState` и `useFetch`
- **Утилиты:** [VueUse](https://vueuse.org/)

## Структура проекта

```
├── app/
│   └── app.vue                # Корневой компонент приложения
├── assets/                    
│   └── css/
│       └── main.css           # Основные стили и импорты Tailwind
├── components/                # Компоненты
├── composables/               # Композаблы
├── data/                      # Данные
├── pages/                     # Страницы
├── public/
│   └── images/                # Статические изображения
├── types/
│   └── index.d.ts             # Глобальные типы TypeScript
├── nuxt.config.ts             # Конфигурация Nuxt
├── tailwind.config.js         # Конфигурация Tailwind CSS
└── tsconfig.json              # Конфигурация TypeScript