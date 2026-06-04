export const useBvi = () => {
  // Главный переключатель
  const isEnabled = useCookie<boolean>('bvi_enabled', { default: () => false })
  
  // Настройки
  const fontSize = useCookie<number>('bvi_font_size', { default: () => 16 })
  const theme = useCookie<'black-white' | 'white-black' | 'blue' | 'brown'>('bvi_theme', { default: () => 'white-black' })
  const imagesHidden = useCookie<boolean>('bvi_images_hidden', { default: () => false })
  const letterSpacing = useCookie<string>('bvi_letter_spacing', { default: () => 'normal' }) // normal, wide, wider

  // Методы управления
  const toggleBvi = () => {
    isEnabled.value = !isEnabled.value
    if (!isEnabled.value) {
      // Сброс настроек при выключении
      fontSize.value = 16
      theme.value = 'white-black'
      imagesHidden.value = false
      letterSpacing.value = 'normal'
    }
  }

  const changeFontSize = (step: number) => {
    const newSize = fontSize.value + step
    if (newSize >= 16 && newSize <= 32) fontSize.value = newSize
  }

  const setTheme = (newTheme: 'black-white' | 'white-black' | 'blue' | 'brown') => theme.value = newTheme
  const toggleImages = () => imagesHidden.value = !imagesHidden.value
  const setLetterSpacing = (spacing: string) => letterSpacing.value = spacing

  return {
    isEnabled,
    fontSize,
    theme,
    imagesHidden,
    letterSpacing,
    toggleBvi,
    changeFontSize,
    setTheme,
    toggleImages,
    setLetterSpacing
  }
}