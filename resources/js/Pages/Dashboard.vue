<template>
    <Head title="Dashboard" />

    <BreezeAuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Dashboard
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <h3 class="text-xl font-semibold">Your logged in!</h3>
                        <ul class="mt-4">
                            <li v-for="todo in todos" :key="todo.id" class="p-4 mb-2 bg-gray-100 rounded-lg">
                                <span>{{ todo.title }}</span> <!-- Assuming 'title' is a field in the Todo model -->
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </BreezeAuthenticatedLayout>
</template>

<script setup>
import { defineProps } from 'vue';
import { Head } from '@inertiajs/vue3';
import BreezeAuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

defineProps({
    todos: Array, // Accept the todos prop as an array
});
</script>
