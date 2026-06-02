import { $, chalk, echo } from 'zx';

// Настраиваем zx (чтобы он не падал молча, а красиво выводил логи)
$.verbose = true;

async function runE2E() {
  echo(chalk.bgBlue.white(' 🚀 СТАРТ E2E ТЕСТИРОВАНИЯ \n'));

  try {
    // 1. ПОДЪЕМ ИНФРАСТРУКТУРЫ
    echo(chalk.cyan('📦 1/3: Поднимаем основные сервисы (БД и MinIO)...'));
    // Запускаем и ждем только вечные сервисы
    await $`docker compose -f docker-compose.test.yml up -d --wait test-db test-minio`;
    
    echo(chalk.cyan('🪣 Инициализируем бакеты в MinIO...'));
    // Выполняем скрипт-одноразку (он отработает и сам выключится)
    await $`docker compose -f docker-compose.test.yml up test-minio-init`;
    echo(chalk.green('✔ Инфраструктура полностью готова!\n'));

    // 2. МИГРАЦИИ БАЗЫ ДАННЫХ
    echo(chalk.cyan('🗄️ 2/3: Накатываем миграции Prisma...'));
    // В TypeScript мы можем безопасно и кроссплатформенно задать переменную окружения
    process.env.DATABASE_URL = 'postgresql://test_user:test_password@localhost:5432/test_recipes?schema=public';
    await $`pnpm exec prisma migrate deploy`;
    echo(chalk.green('✔ База данных готова!\n'));

    // 3. ЗАПУСК ТЕСТОВ
    echo(chalk.cyan('🎭 3/3: Запускаем Playwright...'));
    await $`pnpm exec playwright test --ui`;
    echo(chalk.bgGreen.black(' 🎉 ВСЕ ТЕСТЫ УСПЕШНО ПРОЙДЕНЫ! \n'));

  } catch (error) {
    // ЕСЛИ ЧТО-ТО УПАЛО (на любом из этапов)
    echo(chalk.bgRed.white(' ❌ ОШИБКА ВО ВРЕМЯ ВЫПОЛНЕНИЯ! '));
    echo(chalk.red('Скрипт был прерван. Смотри логи выше.'));
    
    // Принудительно завершаем процесс с кодом ошибки (чтобы GitHub Actions понял, что тест упал)
    process.exitCode = 1;

  } finally {
    // БЛОК ОЧИСТКИ (Выполнится ВСЕГДА, даже если тесты упали с ошибкой)
    echo(chalk.yellow('\n🧹 Убираем за собой... Останавливаем контейнеры.'));
    await $`docker compose -f docker-compose.test.yml down`;
    echo(chalk.green('✔ Инфраструктура свернута.'));
  }
}

runE2E();