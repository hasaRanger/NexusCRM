<template>
  <Teleport to="body">
    <Transition
      enter-active-class="transition ease-out duration-300"
      enter-from-class="opacity-0 translate-y-2"
      enter-to-class="opacity-100 translate-y-0"
      leave-active-class="transition ease-in duration-300"
      leave-from-class="opacity-100 translate-y-0"
      leave-to-class="opacity-0 translate-y-2"
    >
      <div
        v-if="isVisible && message"
        class="fixed top-4 right-4 z-50 max-w-md"
      >
        <div class="bg-green-50 dark:bg-green-900/30 border border-green-200 dark:border-green-700 rounded-lg shadow-lg p-4 flex items-start gap-3">
          <!-- Success Icon -->
          <div class="flex-shrink-0 mt-0.5">
            <svg class="h-5 w-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
            </svg>
          </div>

          <!-- Message Content -->
          <div class="flex-1">
            <p class="text-sm font-medium text-green-900 dark:text-green-200">{{ message }}</p>
          </div>

          <!-- Close Button -->
          <button
            @click="dismiss"
            class="flex-shrink-0 text-green-600 hover:text-green-700 transition-colors"
            aria-label="Dismiss message"
          >
            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
            </svg>
          </button>
        </div>
      </div>
    </Transition>
  </Teleport>
</template>

<script setup>
import { computed, ref, watch, onMounted } from 'vue'
import { usePage } from '@inertiajs/vue3'

const $page = usePage()
const isVisible = ref(false)
let dismissTimer = null

const message = computed(() => $page.props.flash?.success)

const dismiss = () => {
  isVisible.value = false
  if (dismissTimer) {
    clearTimeout(dismissTimer)
  }
}

watch(message, (newMessage) => {
  if (newMessage) {
    isVisible.value = true

    // Auto-dismiss after 3 seconds
    if (dismissTimer) {
      clearTimeout(dismissTimer)
    }
    dismissTimer = setTimeout(() => {
      isVisible.value = false
    }, 3000)
  }
}, { immediate: true })

onMounted(() => {
  // Clean up timer on unmount
  return () => {
    if (dismissTimer) {
      clearTimeout(dismissTimer)
    }
  }
})
</script>
