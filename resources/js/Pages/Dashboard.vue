<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
</script>

<template>
    <Head title="Cookbook" />
    <AuthenticatedLayout>
        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <h1 class="text-2xl font-bold text-gray-800">Cookbook: Greenfield Enterprise</h1>
                <p class="mt-4">Скелет Vue 3 + Inertia + Tailwind успешно установлен.</p>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
