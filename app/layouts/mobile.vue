<template>
	<div class="layout layout_mobile">
		<main class="grow flex flex-col">
			привет
			<slot />
		</main>
	</div>
</template>

<script setup>

</script>

<style lang="scss">
.layout_mobile {
	min-width: 320px;
}
</style>