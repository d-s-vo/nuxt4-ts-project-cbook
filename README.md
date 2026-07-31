# 🍽️ Cookbook

> Веб-приложение для хранения, просмотра и поиска кулинарных рецептов.

Пользователь просматривает каталог рецептов, открывает отдельный рецепт, добавляет новый
(с загрузкой изображения) и удаляет. Есть панель доступности для слабовидящих.

> ⚠️ **Статус: идёт миграция стека.** Проект переводится с прежней версии на Nuxt/Prisma
> на стек Laravel. Инструкции ниже описывают **целевой** стек; часть шагов станет актуальной
> по мере переноса кода.

## 🛠 Технический стек (целевой)

* **Бэкенд:** PHP 8.4 · Laravel 12 · MySQL 8
* **Фронтенд:** Inertia.js · Vue 3 (`<script setup>`, TypeScript strict) · Tailwind CSS
* **Слой данных:** Spatie Laravel Data (типизированные DTO) + автогенерация TS-типов
* **Админ-панель:** Filament 5
* **Медиа:** `whyme-agency/laravel-media` (загрузка и обработка изображений)
* **Качество:** PHPStan (Level 10) · Laravel Pint (PSR-12) · Pest (тесты)
* **Локальное окружение:** Laravel Sail (Docker)

## 🚀 Локальный запуск (Sail)

```bash
# 1. зависимости
composer install
cp .env.example .env

# 2. окружение в Docker: sail up поднимает MySQL 8, Redis и Mailpit (UI :8025)
./vendor/bin/sail up -d
./vendor/bin/sail artisan key:generate

# 3. схема БД
./vendor/bin/sail artisan migrate

# 4. фронтенд
pnpm install
pnpm dev
```

Приложение — на локальном порту Sail (`APP_PORT`), Vite — на `:5173`, админка Filament — по `/admin`.
Почта перехватывается Mailpit — веб-интерфейс на `http://localhost:8025`.

Регистрация требует подтверждения email: до перехода по ссылке из письма доступна только страница
с просьбой подтвердить адрес. Локально письма никуда не уходят — их перехватывает Mailpit (UI на `:8025`).

## 💻 Основные команды

```bash
./vendor/bin/sail up -d                    # поднять окружение (MySQL 8, Redis, Mailpit)
./vendor/bin/sail artisan migrate          # применить миграции
pnpm dev                                   # Vite dev-сервер
pnpm build                                 # прод-сборка фронтенда

cp .env.testing.example .env.testing       # профиль тестовой БД (один раз)
./vendor/bin/sail artisan test             # тесты (Pest)
./vendor/bin/sail bin pint --test          # стиль кода (PSR-12)
./vendor/bin/sail bin phpstan analyse      # статический анализ (Level 10)
./vendor/bin/sail artisan typescript:transform   # генерация TS-типов из DTO
```

## 🏛 Архитектура (слоистая)

Строгий однонаправленный поток данных:

```
Request (FormRequest) → Controller → Task → Repository → DTO (Spatie Data) → Page Resolver → Inertia (Vue)
```

| Слой | Каталог | Ответственность |
|------|---------|-----------------|
| Модели | `app/Models` | Eloquent-сущности (доступ к БД — только через Repository) |
| Репозитории | `app/Data/Repositories` | единственное место запросов к БД (Eloquent / `DB`) |
| DTO | `app/Data` | типизированные объекты передачи данных (Spatie Data) |
| Задачи | `app/Tasks` | одна бизнес-операция = один Task |
| Резолверы | `app/Resolvers/Page` | сборка пропсов страницы для Inertia |
| Админка | `app/Filament` | ресурсы Filament (без бизнес-логики) |
| Фронтенд | `resources/js` | Inertia-страницы и Vue-компоненты (получают только DTO) |

**Правила:** каждый `.php` — `declare(strict_types=1)`; наружу (в Inertia/Vue) отдаются только
DTO, не сырые модели; вся серверная валидация — через Form Requests.

## 📦 Доменная модель

* **Recipe** — `title`, `description`, `cooking_time`, `servings`, `difficulty` (low/medium/high),
  `steps` (JSON-массив шагов).
* **Ingredient** — `name`, `quantity`, `unit`; связь `hasMany` c `Recipe` (каскадное удаление).
