<template>
    <input
        :type="type"
        :value="modelValue"
        class="form-input"
    />
</template>
<script setup lang="ts">
    defineProps<{
        modelValue: string;
        type: string;
    }>();

    const modelValue = defineModel<string | number>();
</script>