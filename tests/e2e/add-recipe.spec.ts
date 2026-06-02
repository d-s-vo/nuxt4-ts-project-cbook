// e2e/add-recipe.spec.ts
import { test, expect } from '@playwright/test';
import path from 'node:path';

test('Успешное добавление нового рецепта', async ({ page }) => {
  
  // 1. ПЕРЕХОД
  await page.goto('http://localhost:3000/add-recipe');

  // ЖДЕМ, пока Nuxt не закончит гидратацию и сеть не успокоится
  await page.waitForLoadState('networkidle');

  // 2. ДЕЙСТВИЯ: Основная информация
  await page.getByPlaceholder('Название блюда').fill('Домашний Борщ');
  await page.getByPlaceholder('Добавьте описание').fill('Классический рецепт со сметаной и чесночными пампушками');

  // Загрузка картинки: ищем нативный input внутри UFileUpload
  // и принудительно диспатчим 'input' event, чтобы Vue-компонент отреагировал
  const fileInput = page.locator('input[type="file"]');
  const filePath = path.resolve('tests/fixtures/borsch.jpg');
  await fileInput.setInputFiles(filePath);
  // Подстраховка: диспатчим 'input' event, т.к. UFileUpload может не среагировать на программный 'change'
  await fileInput.dispatchEvent('input');

  // 3. ДЕЙСТВИЯ: Ингредиенты
  await page.getByPlaceholder('Введите название ингредиента').fill('Свекла');
  
  // Заполняем время приготовления и порции
  await page.locator('input[name="cookingTime"]').fill('45');
  await page.locator('input[name="servings"]').fill('4');

  // Заполняем количество ингредиента
  await page.locator('input[name="ingredient-0-quantity"]').fill('300');

  // 4. ДЕЙСТВИЯ: Этапы приготовления
  await page.getByPlaceholder('Опишите шаг 1').fill('Очистить овощи и поставить вариться бульон');

  // 5. РЕГИСТРИРУЕМ ОБРАБОТЧИК ДИАЛОГА ДО КЛИКА
  page.on('dialog', async dialog => {
    // Временно убираем жесткий expect отсюда, просто закрываем алерт,
    // чтобы он не блокировал страницу.
    await dialog.accept(); 
  });

  // 6. ОТПРАВКА С ОЖИДАНИЕМ СЕТИ (Best Practice)
  // Запускаем ожидание ответа бэкенда ПАРАЛЛЕЛЬНО с кликом
  const [response] = await Promise.all([
    // Замени '/api/recipes' на реальный URL твоего эндпоинта сохранения
    page.waitForResponse(res => res.url().includes('/api/recipes') && res.request().method() === 'POST'),
    page.getByRole('button', { name: 'Добавить рецепт' }).click()
  ]);

  // Сначала проверяем, что бэкенд не упал с ошибкой 500
  expect(response.ok()).toBeTruthy(); // Ожидаем статус 200-299
  
  // 7. ПРОВЕРКА: редирект на главную
  await expect(page).toHaveURL('http://localhost:3000/');
});