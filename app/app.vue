<template>
  <div class="min-h-screen bg-slate-50 font-sans p-8">
    <div class="max-w-4xl mx-auto">
      <header class="text-center mb-12">
        <h1 class="text-4xl font-bold text-slate-800 mb-4">
          Vue TypeScript Cookbook
        </h1>
        <p class="text-xl text-slate-600">
          Nuxt 4 + TypeScript + Vue 3 Composition API + Tailwind CSS
        </p>
      </header>

      <!-- Status Cards -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-12">
        <div class="bg-white p-6 rounded-xl shadow-sm text-center">
          <div class="text-3xl mb-2">✅</div>
          <div class="font-semibold text-slate-800">Nuxt 4.1.1</div>
          <div class="text-sm text-slate-500">Framework</div>
        </div>
        
        <div class="bg-white p-6 rounded-xl shadow-sm text-center">
          <div class="text-3xl mb-2">🔷</div>
          <div class="font-semibold text-slate-800">TypeScript</div>
          <div class="text-sm text-slate-500">Type Safety</div>
        </div>
        
        <div class="bg-white p-6 rounded-xl shadow-sm text-center">
          <div class="text-3xl mb-2">⚡</div>
          <div class="font-semibold text-slate-800">Vue 3</div>
          <div class="text-sm text-slate-500">Composition API</div>
        </div>
      </div>

      <!-- Interactive Demo -->
      <div class="bg-white p-8 rounded-xl shadow-sm">
        <h3 class="text-2xl font-bold text-slate-800 mb-8 text-center">Interactive Demo</h3>
        
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
          <!-- Counter Demo -->
          <div>
            <h4 class="text-lg font-semibold mb-4 text-gray-700">TypeScript Counter</h4>
            <div class="text-center">
              <div class="text-5xl font-bold text-blue-500 mb-4">{{ counter }}</div>
              <div class="flex gap-2 justify-center">
                <button 
                  @click="increment"
                  class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg font-medium transition-colors"
                >
                  +
                </button>
                <button 
                  @click="decrement"
                  class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg font-medium transition-colors"
                >
                  -
                </button>
                <button 
                  @click="reset"
                  class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg font-medium transition-colors"
                >
                  Reset
                </button>
              </div>
            </div>
          </div>

          <!-- Form Demo -->
          <div>
            <h4 class="text-lg font-semibold mb-4 text-gray-700">Reactive Form</h4>
            <div class="space-y-4">
              <input 
                v-model="userInput"
                type="text"
                placeholder="Type something..."
                class="w-full p-3 border border-gray-300 rounded-lg text-base focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
              >
              <div class="text-sm text-gray-600">
                <strong>You typed:</strong> {{ userInput || 'Nothing yet...' }}
              </div>
              <div class="text-sm text-gray-600">
                <strong>Character count:</strong> {{ characterCount }}
              </div>
            </div>
          </div>
        </div>

        <!-- Tech Stack Info -->
        <div class="mt-8 pt-8 border-t border-gray-200">
          <h4 class="text-lg font-semibold mb-4 text-gray-700">Tech Stack Details</h4>
          <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm text-gray-600">
            <div><strong>Node.js:</strong> {{ nodeVersion }}</div>
            <div><strong>Package Manager:</strong> pnpm</div>
            <div><strong>Build Tool:</strong> Vite</div>
            <div><strong>Dev Server:</strong> localhost:3000</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
// Reactive state with TypeScript
const counter = ref<number>(0)
const userInput = ref<string>('')

// Computed properties with TypeScript
const characterCount = computed<number>(() => userInput.value.length)
const nodeVersion = computed<string>(() => '20.19.5')

// Methods with TypeScript annotations
const increment = (): void => {
  counter.value++
}

const decrement = (): void => {
  counter.value--
}

const reset = (): void => {
  counter.value = 0
}

// Lifecycle hooks
onMounted(() => {
  console.log('🎉 Vue TypeScript Cookbook is ready!')
  console.log('✅ Nuxt 4 + TypeScript + Vue 3 Composition API working!')
})

// SEO Meta
useSeoMeta({
  title: 'Vue TypeScript Cookbook',
  description: 'Modern Vue.js boilerplate with Nuxt 4, TypeScript, Vue 3 Composition API'
})
</script>
