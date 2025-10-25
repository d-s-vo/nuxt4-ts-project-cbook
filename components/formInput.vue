<template>
    <input
        v-if="type !== 'textarea'"
        :type="type"
        v-model="modelValue"
        class="form-input"
        :placeholder="placeholder"
    />
    <textarea
      v-else
      rows="3"
      v-model="modelValue"
      class="form-input"
      :placeholder="placeholder"
    />
</template>
<script setup lang="ts">
    import { watch } from 'vue';
    
    const props = defineProps<{
        type: 'text' | 'number' | 'textarea';
        placeholder?: string;
    }>();

    const modelValue = defineModel<string | number>();

    // следим за изменением и приводим к типу
    watch(modelValue, (val) => {
    if (props.type === 'number' && typeof val === 'string') {
        const parsed = Number(val);
        modelValue.value = isNaN(parsed) ? 0 : parsed; // защита от NaN
    }
    });
</script>