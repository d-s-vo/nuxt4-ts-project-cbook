/**
 * Composable для управления панелью для слабовидящих (BVI Panel)
 * Соответствует требованиям ГОСТ Р 52872-2019 (WCAG 2.1 уровень AA)
 * Использует useCookie для SSR-safe хранения состояния
 */
export const useBvi = () => {
  // Типы для состояния BVI
  type BviTheme = 'white-black' | 'black-white' | 'blue'
  type BviSpacing = 'normal' | 'medium' | 'large'
  type BviImageMode = 'default' | 'grayscale' | 'hidden'

  // Главный переключатель панели
  const isEnabled = useCookie<boolean>('bvi_enabled', { default: () => false })
  
  // Размер шрифта (default: 16, min: 16, max: 32)
  const fontSize = useCookie<number>('bvi_font_size', { default: () => 16 })
  
  // Цветовая тема
  const theme = useCookie<BviTheme>('bvi_theme', { default: () => 'white-black' })
  
  // Интервалы текста
  const spacing = useCookie<BviSpacing>('bvi_spacing', { default: () => 'normal' })
  
  // Режим отображения изображений
  const imageMode = useCookie<BviImageMode>('bvi_image_mode', { default: () => 'default' })
  
  // Включен ли синтез речи при наведении
  const speechEnabled = useCookie<boolean>('bvi_speech_enabled', { default: () => false })

  /**
   * Переключатель панели BVI
   * При выключении сбрасывает все настройки в дефолтные и останавливает речь
   */
  const toggleBvi = () => {
    isEnabled.value = !isEnabled.value
    if (!isEnabled.value) {
      // Сброс всех настроек в дефолтные значения при выключении
      fontSize.value = 16
      theme.value = 'white-black'
      spacing.value = 'normal'
      imageMode.value = 'default'
      speechEnabled.value = false
      stopSpeech() // Принудительная остановка речи
    }
  }

  /**
   * Изменение размера шрифта с проверкой границ (16-32px)
   */
  const setFontSize = (size: number) => {
    if (size >= 16 && size <= 32) {
      fontSize.value = size
    }
  }

  /**
   * Увеличение размера шрифта на 2px
   */
  const increaseFontSize = () => {
    const newSize = fontSize.value + 2
    if (newSize <= 32) fontSize.value = newSize
  }

  /**
   * Уменьшение размера шрифта на 2px
   */
  const decreaseFontSize = () => {
    const newSize = fontSize.value - 2
    if (newSize >= 16) fontSize.value = newSize
  }

  /**
   * Установка цветовой темы
   */
  const setTheme = (newTheme: BviTheme) => {
    theme.value = newTheme
  }

  /**
   * Установка режима интервалов текста
   */
  const setSpacing = (newSpacing: BviSpacing) => {
    spacing.value = newSpacing
  }

  /**
   * Установка режима отображения изображений
   */
  const setImageMode = (newMode: BviImageMode) => {
    imageMode.value = newMode
  }

  /**
   * Переключатель синтеза речи
   * При выключении останавливает текущую речь
   */
  const toggleSpeech = () => {
    speechEnabled.value = !speechEnabled.value
    if (!speechEnabled.value) {
      stopSpeech()
    }
  }

  /**
   * Синтез речи с использованием Web Speech API
   * SSR-safe: выполняется только на клиенте
   * Прерывает предыдущую речь перед началом новой
   */
  const speak = (text: string) => {
    // Проверка: речь включена и код выполняется на клиенте
    if (!speechEnabled.value || !import.meta.client) return

    // Отмена предыдущей речи для предотвращения наложения
    stopSpeech()

    const utterance = new SpeechSynthesisUtterance(text)
    utterance.lang = 'ru-RU' // Русский язык
    utterance.rate = 1.0 // Стандартная скорость (0.1 - 10)
    utterance.pitch = 1.0 // Стандартная высота тона (0 - 2)

    window.speechSynthesis.speak(utterance)
  }

  /**
   * Остановка текущего синтеза речи
   * SSR-safe: выполняется только на клиенте
   */
  const stopSpeech = () => {
    if (import.meta.client) {
      window.speechSynthesis.cancel()
    }
  }

  return {
    // State
    isEnabled,
    fontSize,
    theme,
    spacing,
    imageMode,
    speechEnabled,
    
    // Actions
    toggleBvi,
    setFontSize,
    increaseFontSize,
    decreaseFontSize,
    setTheme,
    setSpacing,
    setImageMode,
    toggleSpeech,
    speak,
    stopSpeech
  }
}