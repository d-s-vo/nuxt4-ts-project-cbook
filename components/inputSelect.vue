<template>
    <select
        v-model="model"
        class="w-full form-input"
    >
        <option
        v-for="option in options"
        :key="option.value"
        :value="option.value"
        >
        {{ option.title }}
        </option>
    </select>
</template>
<script setup lang="ts">
    defineProps<{ 
        options: { value: string; title: string }[] 
    }>();

    const model = defineModel<string>();
</script>
