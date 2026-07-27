# CLAUDE.md

Контекст проекта для Claude Code. Загружается автоматически в каждую сессию.

## Что это

**Cookbook** — фуллстек‑приложение каталога кулинарных рецептов. Учебный проект.
Пользователь может просматривать рецепты, открывать один рецепт, добавлять новый (с
загрузкой изображения) и удалять. Есть панель доступности для слабовидящих (BVI).

Язык интерфейса и большинства комментариев — русский.

## Стек

- **Фреймворк:** Nuxt 4 (`compatibilityVersion: 4`) / Vue 3, Composition API, `<script setup>`
- **Язык:** TypeScript (строгий, `typescript.typeCheck: true` в `nuxt.config.ts`)
- **UI:** Nuxt UI 4 (`@nuxt/ui`, компоненты `U*`) + Tailwind CSS
- **БД:** PostgreSQL (облако Neon) через Prisma 7 (`@prisma/client` + `@prisma/adapter-pg`)
- **Файлы:** MinIO (S3‑совместимое, AWS S3 SDK), прокинут в интернет через Cloudflare Tunnel
- **Тесты:** Playwright (E2E), Vitest (unit)
- **Пакетный менеджер:** pnpm (v10; в CI используется v8)
- **Инфра:** Docker Compose, GitHub Actions

## Команды

Все скрипты — из `package.json` (используй именно их, других нет):

```bash
pnpm dev              # dev-сервер → http://localhost:3000
pnpm build            # prisma generate + nuxt build (прод-сборка)
pnpm preview          # запуск собранной версии
pnpm generate         # статическая генерация
pnpm typecheck        # строгая проверка типов (nuxt typecheck)
pnpm vitest run       # unit-тесты
pnpm test:e2e         # полный E2E-цикл (оркестратор scripts/run-e2e.ts, поднимает контейнеры)
pnpm test:env:down    # погасить тестовые контейнеры
pnpm studio           # Prisma Studio (веб-просмотр БД)
pnpm exec prisma migrate dev   # создать/применить миграцию после правки schema.prisma
```

Примечания:
- **Скрипта `pnpm lint` НЕТ** (в README упомянут, но в `package.json` отсутствует). Для проверки качества используй `pnpm typecheck`.
- Инфраструктура для dev: `docker compose up -d` (Postgres + MinIO + cloudflared).
- Для связи Node ↔ Docker в `.env` используется `127.0.0.1`, а не `localhost` (во избежание проблем IPv6/IPv4).

## Архитектура

```
app/            # Фронтенд
  pages/        # index.vue (список), add-recipe.vue (форма), recipe-page.vue (просмотр)
  components/   # recipeCard.vue, BviPanel.vue, icons/
  layouts/      # desktop.vue, mobile.vue (переключение через @nuxtjs/device)
  composables/  # useRecipes.ts (обёртки над API), useBvi.ts
  plugins/      # bvi-speech-tracker.client.ts (озвучка для слабовидящих)
  utils/        # validations.ts (zod-схема формы — ТОЛЬКО клиентская)
server/         # Бэкенд (Nitro)
  api/          # recipes.get.ts, recipes.post.ts, recipes/[id].get.ts, recipes/[id].delete.ts
  utils/        # prisma.ts (единый инстанс PrismaClient с pg-адаптером)
shared/types/   # recipe.types.ts, form.types.ts — общие типы фронта и бэка (алиас ~~/shared)
data/           # mockFormField.ts — декларативное описание полей формы add-recipe
prisma/         # schema.prisma, migrations/, seed.ts, generated/ (git-ignored)
scripts/        # run-e2e.ts — оркестратор E2E на zx
tests/          # e2e/ (Playwright), recipe-schema.test.ts (Vitest), fixtures/
```

### Модель данных (`prisma/schema.prisma`)
- `Recipe` (title, description, cookingTime, servings, difficulty, imageUrl?, steps[]) →
  `Ingredient[]` (name, quantity: Float, unit), связь с `onDelete: Cascade`.
- `enum Difficulty { LOW="легко", MEDIUM="средне", HIGH="сложно" }` — в БД хранятся русские значения через `@map`.

### Поток добавления рецепта
1. `add-recipe.vue` строит форму из `data/mockFormField.ts`, валидирует через zod (`app/utils/validations.ts`).
2. Данные собираются в `FormData` (объекты → `JSON.stringify`, файл → как есть) и уходят в `POST /api/recipes`.
3. `recipes.post.ts` парсит multipart, грузит картинку в S3 (`ACL: public-read`), пишет запись в БД.

## Конвенции

- Типы фронта и бэка — общие, живут в `shared/types`, импорт через алиас `~~/shared/...`.
- Импорт клиентских утилит — через `~/...`, серверных — относительными путями.
- Prisma-клиент генерируется в `prisma/generated/client` (git-ignored) — после правки схемы нужен `prisma generate` (входит в `pnpm build`).
- Форма описывается декларативно в `data/mockFormField.ts`, а не хардкодится в шаблоне.
- Стиль: русские комментарии, `<script setup lang="ts">`, композаблы для доступа к API.

## Известные проблемы безопасности

⚠️ Эти дыры известны — учитывай при правках, не воспроизводи паттерн, при возможности чини:

1. **Нет аутентификации/авторизации** — все API-эндпоинты (включая `DELETE`) открыты анонимно.
2. **Нет серверной валидации** — zod-схема работает только на клиенте; `recipes.post.ts` слепо доверяет телу запроса. Идеально — вынести схему в `shared/` и валидировать на сервере.
3. **Утечка ошибок наружу** — `recipes.post.ts` возвращает клиенту `realS3Error`, `bucketName`, `detail` (отладочный код). Не тиражировать.
4. **Загрузка файлов**: MIME проверяется по заголовку (подделываем), нет серверного лимита размера, имя файла клиента идёт прямо в ключ S3, объекты `public-read`.
5. **Секреты**: `.env` содержит боевые креды Neon; MinIO с дефолтными ключами выставлен наружу через Cloudflare Tunnel. `.env` НЕ коммитить (уже в `.gitignore`).

## Прочее

- В `nuxt.config.ts` захардкожен `runtimeConfig.public.siteUrl` (Vercel URL).
- Тестовое окружение изолировано: `docker-compose.test.yml` + `.env.test` (креды `test_*`).
