# 🍽️ Cookbook

> Современное, отказоустойчивое веб-приложение для хранения и поиска кулинарных рецептов. 

Проект построен на базе Nuxt 4 с полноценным бэкендом (API Routes), реляционной базой данных и интеграцией с S3-совместимым облачным хранилищем для изображений. Настроен непрерывный цикл интеграции (CI/CD) и сквозное E2E-тестирование.

## 🛠 Технический стек

* **Фреймворк:** [Nuxt 4](https://nuxt.com/) / Vue 3
* **Язык:** [TypeScript](https://www.typescriptlang.org/)
* **Стили и UI:** [Tailwind CSS](https://tailwindcss.com/)
* **База данных:** PostgreSQL + [Prisma ORM](https://www.prisma.io/)
* **Хранилище файлов:** MinIO (AWS S3 SDK)
* **Тестирование:** [Playwright](https://playwright.dev/) (E2E)
* **Инфраструктура:** Docker, GitHub Actions

---

## 📋 Предварительные требования

Для комфортного запуска проекта локально потребуются:

* **Node.js 20+** (Рекомендуемая версия: 20.19.5 LTS)
* **pnpm 9+** (Менеджер пакетов: `npm install -g pnpm@9`)
* **Docker & Docker Compose** (Для локального запуска базы данных и S3 хранилища)

---

## 🚀 Локальная установка и запуск

**1. Клонирование и установка зависимостей**

```bash
git clone [https://github.com/d-s-vo/nuxt4-ts-project-cbook](https://github.com/d-s-vo/nuxt4-ts-project-cbook)
cd nuxt4-ts-project-cbook
pnpm install
```

**2. Настройка переменных окружения**

Создайте файлы .env и .env.test в корне проекта.

⚠️ Важно: Для связи между Node.js и Docker-контейнерами используйте 127.0.0.1 вместо localhost, чтобы избежать конфликтов маршрутизации IPv6/IPv4 в современных системах.

Пример локального .env:

```bash
   # База данных
   DATABASE_URL="postgresql://local_user:local_password@127.0.0.1:5432/recipes_db?schema=public"

   # MinIO (S3 Хранилище)
   S3_ENDPOINT="[http://127.0.0.1:9000](http://127.0.0.1:9000)"
   S3_ACCESS_KEY="local_access_key"
   S3_SECRET_KEY="local_secret_key_123"
   S3_BUCKET="recipes"
   S3_REGION="us-east-1"
```

**3. Запуск инфраструктуры (Docker)**

Поднимите локальную базу данных Postgres и хранилище MinIO:

```bash
docker compose up -d
```

**4. Инициализация базы данных**
Примените миграции Prisma, чтобы создать таблицы:

```bash
pnpm prisma migrate dev
```

**5. Запуск сервера разработки**

```bash
pnpm dev
```

Фронтенд приложения доступен по адресу: http://localhost:3000

Консоль хранилища MinIO доступна по адресу: http://127.0.0.1:9001

## 💻 Основные команды (Скрипты)
### Разработка и сборка

```bash
pnpm dev — Запуск в режиме разработки.
```

```bash
pnpm build — Сборка приложения для продакшена.
```

```bash
pnpm preview — Локальный запуск собранной версии.
```

```bash
pnpm typecheck — Строгая проверка типов TypeScript.
```

```bash
pnpm lint — Проверка кода линтером.
```

База данных (Prisma):

```bash
pnpm prisma studio — Запуск удобного веб-интерфейса для просмотра и редактирования записей в БД.
```

```bash
pnpm prisma migrate dev — Создание и применение новых миграций после изменения схемы в schema.prisma.
```

Тестирование (Playwright):

```bash
pnpm run test:e2e — Полный цикл сквозного тестирования (автоматически поднимает тестовые контейнеры, накатывает миграции, запускает тесты и очищает среду).
```

```bash
pnpm run test:e2e:ui — Запуск E2E тестов с визуальным UI-интерфейсом для отладки и просмотра шагов.
```

## 📁 Структура проекта
Plaintext
├── app/                      # Фронтенд: главный файл app.vue, страницы и компоненты
├── server/                   # Бэкенд: API эндпоинты (/api/*), логика Prisma и S3
├── prisma/                   # Схема базы данных (schema.prisma) и миграции
├── tests/e2e/                # Сценарии сквозного тестирования Playwright
├── scripts/                  # Вспомогательные скрипты (оркестратор run-e2e.ts)
├── .github/workflows/        # Конфигурации CI/CD конвейеров для GitHub Actions
├── docker-compose.yml        # Инфраструктура для локальной разработки (БД + MinIO)
├── docker-compose.test.yml   # Изолированная инфраструктура для E2E-тестов
├── playwright.config.ts      # Конфигурация тестового робота и его окружения
└── nuxt.config.ts            # Главный конфигурационный файл Nuxt 4