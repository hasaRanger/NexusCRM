<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gradient-to-br from-indigo-500 to-indigo-100 dark:from-indigo-950 dark:to-background">
        <div>
            <Link href="/">
                <!-- <ApplicationLogo class="w-20 h-20 fill-current text-gray-500" /> -->
                 <img src="/logo.jpg" alt="Logo" class="w-20 h-20 rounded-full shadow-lg hover:scale-105 transition-transform duration-200 cursor-pointer ease-in-out">
            </Link>
        </div>

        <div
            class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white dark:bg-card shadow-md overflow-hidden sm:rounded-lg"
        >
            <slot />
        </div>
    </div>
</template>
