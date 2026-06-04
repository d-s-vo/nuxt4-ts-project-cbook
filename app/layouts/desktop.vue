<template>
	<div class="layout layout_desktop">
		<main class="grow flex flex-col">
			<slot />
		</main>
	</div>
</template>

<script setup>

</script>

<style lang="scss">
.layout_desktop {
	min-width: 1024px;
	@media (max-width: 1024px) {
		overflow-x: auto;
	}
}
</style>