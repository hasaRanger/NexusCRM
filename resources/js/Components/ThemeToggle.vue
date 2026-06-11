<script setup>
import { useTheme } from '@/composables/useTheme'
import { Sun, Moon } from 'lucide-vue-next'

const { isDark, toggleTheme } = useTheme()
</script>

<template>
    <button
        @click="toggleTheme"
        class="p-2 rounded-md transition-colors duration-200 focus:outline-none"
        :class="[
            isDark
                ? 'text-amber-300 hover:bg-indigo-800/60'
                : 'text-gray-300 hover:bg-indigo-800'
        ]"
        :title="isDark ? 'Switch to light mode' : 'Switch to dark mode'"
        aria-label="Toggle theme"
    >
        <Transition
            enter-active-class="transition-all duration-300 ease-out"
            enter-from-class="opacity-0 rotate-[-90deg] scale-0"
            enter-to-class="opacity-100 rotate-0 scale-100"
            leave-active-class="transition-all duration-200 ease-in absolute"
            leave-from-class="opacity-100 rotate-0 scale-100"
            leave-to-class="opacity-0 rotate-90 scale-0"
            mode="out-in"
        >
            <Moon v-if="isDark" class="w-5 h-5" />
            <Sun v-else class="w-5 h-5" />
        </Transition>
    </button>
</template>
