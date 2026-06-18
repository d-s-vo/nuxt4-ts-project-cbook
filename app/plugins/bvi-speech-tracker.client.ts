/**
 * Плагин синтеза речи для панели BVI
 * Использует глобальное делегирование событий (Event Delegation)
 * для озвучивания текста при наведении курсора без внедрения директив в исходный код
 * 
 * SSR-safe: выполняется только на клиенте благодаря суффиксу .client.ts
 */
export default defineNuxtPlugin((nuxtApp) => {
  // Получаем состояние и методы из composable
  const { speechEnabled, speak, stopSpeech } = useBvi()

  // Кэш текущего элемента для защиты от спама при движении внутри элемента
  let currentElement: HTMLElement | null = null

  /**
   * Обработчик события mouseover
   * Ищет ближайший валидный тег и озвучивает его текст
   */
  const handleMouseOver = (event: MouseEvent) => {
    // Проверка: озвучка включена
    if (!speechEnabled.value) return

    // Находим ближайший валидный тег для озвучивания
    const target = event.target as HTMLElement
    const validElement = target.closest('p, h1, h2, h3, h4, h5, h6, a, button, li, label, figcaption') as HTMLElement | null

    // Если валидный элемент не найден - выход
    if (!validElement) return

    // Защита от спама: не зачитывать текст заново, если курсор двигается внутри того же элемента
    if (currentElement === validElement) return

    // Обновляем кэш текущего элемента
    currentElement = validElement

    // Получаем текст элемента и убираем лишние пробелы
    const text = validElement.innerText?.trim()

    // Если текст пустой - не озвучиваем
    if (!text) return

    // Озвучиваем текст
    speak(text)
  }

  /**
   * Обработчик события mouseout
   * Останавливает речь, если курсор ушел за пределы текущего валидного элемента
   */
  const handleMouseOut = (event: MouseEvent) => {
    // Проверка: озвучка включена
    if (!speechEnabled.value) return

    // Находим ближайший валидный тег
    const target = event.target as HTMLElement
    const validElement = target.closest('p, h1, h2, h3, h4, h5, h6, a, button, li, label, figcaption') as HTMLElement | null

    // Если курсор ушел за пределы текущего элемента - останавливаем речь
    if (validElement !== currentElement) {
      stopSpeech()
      currentElement = null
    }
  }

  /**
   * Инициализация плагина после монтирования приложения
   * Используем хук app:mounted для гарантии работы с DOM
   */
  nuxtApp.hook('app:mounted', () => {
    // Вешаем слушатели событий на глобальный document
    // Используем пассивный режим для улучшения производительности
    document.addEventListener('mouseover', handleMouseOver, { passive: true })
    document.addEventListener('mouseout', handleMouseOut, { passive: true })
  })

  /**
   * Примечание: Nuxt автоматически обрабатывает очистку для client-side плагинов
   * Слушатели событий будут удалены при уничтожении приложения
   */
})
