<template>
  <!-- Панель для слабовидящих с классом .bvi-panel для изоляции от глобальных CSS -->
  <div 
    v-if="isEnabled"
    class="bvi-panel sticky top-0 w-full z-[9999] bg-white border-b-4 border-black shadow-lg"
  >
    <div class="max-w-7xl mx-auto px-4 py-4">
      <div class="flex justify-between flex-wrap gap-[10px]">
        
        <!-- Размер текста -->
        <div class="flex flex-col gap-2">
          <span class="text-black font-bold">Размер текста</span>
          <div class="flex items-center ">
            <button
              @click="decreaseFontSize"
              :disabled="fontSize <= 16"
              :aria-label="fontSize <= 16 ? 'Размер шрифта минимальный' : 'Уменьшить размер шрифта'"
              :aria-pressed="false"
              class="
                 text-black font-medium 
                hover:bg-[#F5F5F5] disabled:opacity-50 disabled:cursor-not-allowed focus:ring-2 
                focus:ring-black focus:outline-none transition-colors"
            >
              А-
            </button>
            <span class="text-black font-medium min-w-[3rem] text-center text-sm">{{ fontSize }}px</span>
            <button
              @click="increaseFontSize"
              :disabled="fontSize >= 32"
              :aria-label="fontSize >= 32 ? 'Размер шрифта максимальный' : 'Увеличить размер шрифта'"
              :aria-pressed="false"
              class="
                
                text-black font-medium text-sm hover:bg-[#F5F5F5]
                disabled:opacity-50 disabled:cursor-not-allowed 
                focus:ring-2 focus:ring-black focus:outline-none transition-colors
              "
            >
              А+
            </button>
          </div>
        </div>


        <!-- Цветовая схема -->
        <div class="flex flex-col gap-2">
          <span class="text-black font-bold">Цветовая схема</span>
          <div class="flex items-center gap-2">
            <button
              @click="setTheme('white-black')"
              :aria-pressed="theme === 'white-black'"
              :aria-label="theme === 'white-black' ? 'Тема черный текст на белом фоне выбрана' : 'Черный текст на белом фоне'"
              :class="theme === 'white-black' ? 'bg-white text-black border-black' : 'bg-white border-gray-300 text-black hover:bg-gray-50'"
              class="ium text-sm focus:ring-2 focus:ring-black focus:outline-none transition-colors"
            >
              Ч/Б
            </button>
            <button
              @click="setTheme('black-white')"
              :aria-pressed="theme === 'black-white'"
              :aria-label="theme === 'black-white' ? 'Тема белый текст на черном фоне выбрана' : 'Белый текст на черном фоне'"
              :class="theme === 'black-white' ? 'bg-black text-white border-black' : 'bg-white border-gray-300 text-black hover:bg-gray-50'"
              class="ium text-sm focus:ring-2 focus:ring-black focus:outline-none transition-colors"
            >
              Б/Ч
            </button>
            <button
              @click="setTheme('blue')"
              :aria-pressed="theme === 'blue'"
              :aria-label="theme === 'blue' ? 'Синяя тема выбрана' : 'Синяя тема'"
              :class="theme === 'blue' ? 'bg-[#dbeafe] text-[#1e3a8a] border-[#1e3a8a]' : 'bg-white border-gray-300 text-black hover:bg-gray-50'"
              class="ium text-sm focus:ring-2 focus:ring-black focus:outline-none transition-colors"
            >
              Син
            </button>
          </div>
        </div>

        <!-- Размер элементов -->
        <div class="flex flex-col gap-2">
          <span class="text-black font-bold">Размер межбуквенного интервала</span>
          <div class="flex items-center gap-2">
            <button
              @click="setSpacing('normal')"
              :aria-pressed="spacing === 'normal'"
              :aria-label="spacing === 'normal' ? 'Стандартные интервалы выбраны' : 'Стандартные интервалы'"
              :class="spacing === 'normal' ? 'shadow-[0_0_0_2px_rgba(0,0,0)]' : ''"
              class="ium text-sm focus:ring-2 focus:ring-black focus:outline-none transition-colors"
            >
              Стандарт
            </button>
            <button
              @click="setSpacing('medium')"
              :aria-pressed="spacing === 'medium'"
              :aria-label="spacing === 'medium' ? 'Средние интервалы выбраны' : 'Средние интервалы'"
              :class="spacing === 'medium' ? 'shadow-[0_0_0_2px_rgba(0,0,0)]' : ''"
              class="leading-[1.5] tracking-[0.12em] [word-spacing:0.1em] focus:ring-2 focus:ring-black focus:outline-none transition-colors"
            >
              Средний
            </button>
            <button
              @click="setSpacing('large')"
              :aria-pressed="spacing === 'large'"
              :aria-label="spacing === 'large' ? 'Большие интервалы выбраны' : 'Большие интервалы'"
              :class="spacing === 'large' ? 'shadow-[0_0_0_2px_rgba(0,0,0)]' : ''"
              class="
                ium text-sm
                leading-[2] tracking-[0.2em] [word-spacing:0.15em]
                focus:ring-2 focus:ring-black focus:outline-none transition-colors
              "
            >
              Большой
            </button>
          </div>
        </div>

        <!-- Цветные элементы -->
        <div class="flex flex-col gap-2">
          <span class="text-black font-bold">Режим отображения изображений</span>
          <div class="flex items-center gap-2">
            <button
              @click="setImageMode('default')"
              :aria-pressed="imageMode === 'default'"
              :aria-label="imageMode === 'default' ? 'Цветные изображения выбраны' : 'Цветные изображения'"
              :class="imageMode === 'default' ? 'bg-blue-600 text-white border-blue-600' : 'bg-white border-gray-300 text-black hover:bg-gray-50'"
              class="ium text-sm focus:ring-2 focus:ring-black focus:outline-none transition-colors"
            >
              Цветные
            </button>
            <button
              @click="setImageMode('grayscale')"
              :aria-pressed="imageMode === 'grayscale'"
              :aria-label="imageMode === 'grayscale' ? 'Черно-белые изображения выбраны' : 'Черно-белые изображения'"
              :class="imageMode === 'grayscale' ? 'bg-blue-600 text-white border-blue-600' : 'bg-white border-gray-300 text-black hover:bg-gray-50'"
              class="ium text-sm focus:ring-2 focus:ring-black focus:outline-none transition-colors"
            >
              Ч/Б
            </button>
            <button
              @click="setImageMode('hidden')"
              :aria-pressed="imageMode === 'hidden'"
              :aria-label="imageMode === 'hidden' ? 'Изображения отключены' : 'Отключить изображения'"
              :class="imageMode === 'hidden' ? 'bg-blue-600 text-white border-blue-600' : 'bg-white border-gray-300 text-black hover:bg-gray-50'"
              class="ium text-sm focus:ring-2 focus:ring-black focus:outline-none transition-colors"
            >
              Отключить
            </button>
          </div>
        </div>

        <!-- Синтез речи --> <!-- Кнопка закрытия панели -->
        <div class="flex flex-col gap-2">
          <div class="flex flex-col gap-2 mt-auto">
            <button
              @click="toggleSpeech"
              :aria-pressed="speechEnabled"
              :aria-label="speechEnabled ? 'Озвучка текста включена' : 'Озвучка текста выключена'"
              :class="[
                'ium text-sm focus:ring-2 focus:ring-black focus:outline-none transition-colors flex items-center gap-2',
                speechEnabled ? 'bg-blue-600 text-white border-blue-600' : 'bg-white border-gray-300 text-black hover:bg-gray-50'
              ]"
            >
              {{ speechEnabled ? 'Озвучка текста включена' : 'Озвучка текста выключена' }}
            </button>

            <button
              @click="toggleBvi"
              aria-label="Обычная версия сайта"
              class="px-4 py-1.5 bg-red-600 hover:bg-red-700 text-white border border-red-600 font-medium text-sm focus:ring-2 focus:ring-black focus:outline-none transition-colors"
            >
              Обычная версия
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
/**
 * Компонент панели управления для слабовидящих (BVI Panel)
 * Соответствует требованиям ГОСТ Р 52872-2019 (WCAG 2.1 уровень AA)
 * Использует Tailwind CSS для стилизации
 * Класс .bvi-panel обеспечивает изоляцию от глобальных CSS-перезаписей
 */
import { useBvi } from '~/composables/useBvi'

// Получаем состояние и методы из composable
const {
  isEnabled,
  fontSize,
  theme,
  spacing,
  imageMode,
  speechEnabled,
  toggleBvi,
  increaseFontSize,
  decreaseFontSize,
  setTheme,
  setSpacing,
  setImageMode,
  toggleSpeech
} = useBvi()
</script>

<style scoped>
/**
 * Дополнительные стили для изоляции панели от глобальных BVI-перезаписей
 * Все стили панели должны иметь приоритет над body.bvi-theme-* правилами
 */
.bvi-panel {
  /* Защита от наследования цветов из тем BVI */
  background-color: white !important;
  color: black !important;
  
  /* Защита от наследования интервалов */
  line-height: normal !important;
  letter-spacing: normal !important;
  
  /* Защита от наследования размера шрифта */
  font-size: 16px !important;
}

/* Кнопки панели должны сохранять свои стили */
.bvi-panel button {
  border: 1px solid black;
  border-radius: 6px;
  height: 30px;
  padding: 0 12px;
}

.bvi-panel button.bg-black {
  background-color: black !important;
  color: white !important;
}

.bvi-panel button.bg-green-600 {
  background-color: rgb(22, 163, 74) !important;
  color: white !important;
}

.bvi-panel button.bg-red-600 {
  background-color: rgb(220, 38, 38) !important;
  color: white !important;
}

.bvi-panel button.bg-blue-900 {
  background-color: rgb(30, 58, 138) !important;
  color: white !important;
}

.bvi-panel button:disabled {
  opacity: 0.5 !important;
  cursor: not-allowed !important;
}
</style>
