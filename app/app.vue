<template>
  <div class="min-h-screen font-sans">
      <NuxtLayout :name="isMobileOrTablet ? 'mobile' : 'desktop'">
        <NuxtPage />
      </NuxtLayout>
  </div>
</template>

<script setup lang="ts">
const { isMobileOrTablet } = useDevice();

useHead(() => ({
	bodyAttrs: {
		// у body будет добавлен нужный класс и это далее можно использовать в селекторах
		class: isMobileOrTablet ? 'body_mobile' : 'body_desktop'
	}
}));
</script>