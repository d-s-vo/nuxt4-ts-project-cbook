<template>
    <div>
       {{ recipe }} 
    </div>
</template>
<script setup lang="ts">
import type { Recipe } from '~/types';

defineProps<{
    recipe: Recipe;
}>();
</script>