<template>
  <Head :title="title" />
  <div class="min-h-screen bg-indigo-200 dark:bg-background flex">
    
    <nav class="w-16 lg:w-64 bg-indigo-900 dark:bg-indigo-950 flex flex-col justify-between sticky top-0 h-screen z-20 transition-all duration-300">
      
      <div class="bg-indigo-900 dark:bg-indigo-950">
        <div class="h-16 flex items-center justify-center lg:justify-start lg:px-6">
          <Link href="/dashboard">
            <img src="/logo.jpg" alt="Logo"
              class="w-10 h-10 lg:w-12 lg:h-12 rounded-full hover:scale-105 transition-transform duration-200 cursor-pointer ease-in-out">
          </Link>
        </div>
        
        <div class="flex flex-col space-y-1 mt-6 px-2 lg:px-3 transition-all ease-in-out">
          <Link
            href="/dashboard"
            :class="[
              isActive('/dashboard')
                ? 'bg-indigo-800 dark:bg-indigo-800/60 text-white'
                : 'text-gray-300 hover:bg-indigo-800 dark:hover:bg-indigo-800/40 hover:text-white',
              'p-2 lg:px-3 lg:py-2 rounded-md text-sm font-extrabold transition-colors flex items-center justify-center lg:justify-start'
            ]"
          >
          <CircleGauge class="w-5 h-5 lg:w-4 lg:h-4 lg:mr-2 flex-shrink-0" />
            <span class="hidden lg:block">Dashboard</span>
          </Link>
          <Link
            href="/customers"
            :class="[
              isActive('/customers')
                ? 'bg-indigo-800 dark:bg-indigo-800/60 text-white'
                : 'text-gray-300 hover:bg-indigo-800 dark:hover:bg-indigo-800/40 hover:text-white',
              'p-2 lg:px-3 lg:py-2 rounded-md text-sm font-extrabold transition-colors flex items-center justify-center lg:justify-start'
            ]"
          >
          <UserRound class="w-5 h-5 lg:w-4 lg:h-4 lg:mr-2 flex-shrink-0" />
            <span class="hidden lg:block">Customers</span>
          </Link>
          <Link
            href="/proposals"
            :class="[
              isActive('/proposals')
                 ? 'bg-indigo-800 dark:bg-indigo-800/60 text-white'
                : 'text-gray-300 hover:bg-indigo-800 dark:hover:bg-indigo-800/40 hover:text-white',
              'p-2 lg:px-3 lg:py-2 rounded-md text-sm font-extrabold transition-colors flex items-center justify-center lg:justify-start'
            ]"
          >
          <Handshake class="w-5 h-5 lg:w-4 lg:h-4 lg:mr-2 flex-shrink-0" />
            <span class="hidden lg:block">Proposals</span>
          </Link>
          <Link
            href="/invoices"
            :class="[
              isActive('/invoices')
                ? 'bg-indigo-800 dark:bg-indigo-800/60 text-white'
                : 'text-gray-300 hover:bg-indigo-800 dark:hover:bg-indigo-800/40 hover:text-white',
              'p-2 lg:px-3 lg:py-2 rounded-md text-sm font-extrabold transition-colors flex items-center justify-center lg:justify-start'
            ]"
          >
          <Receipt class="w-5 h-5 lg:w-4 lg:h-4 lg:mr-2 flex-shrink-0" />
            <span class="hidden lg:block">Invoices</span>
          </Link>
          <Link
            href="/transactions"
            :class="[
              isActive('/transactions')
                  ? 'bg-indigo-800 dark:bg-indigo-800/60 text-white'
                  : 'text-gray-300 hover:bg-indigo-800 dark:hover:bg-indigo-800/40 hover:text-white',
              'p-2 lg:px-3 lg:py-2 rounded-md text-sm font-extrabold transition-colors flex items-center justify-center lg:justify-start'
            ]"
          >
          <HandCoins class="w-5 h-5 lg:w-4 lg:h-4 lg:mr-2 flex-shrink-0" />
            <span class="hidden lg:block">Transactions</span>
          </Link>
        </div>
      </div>

      <div class="p-2 lg:p-4 relative">
        <ThemeToggle class="mb-2 mx-auto lg:mx-0" />
        <button
          @click="isProfileOpen = !isProfileOpen"
          class="flex items-center justify-center lg:justify-start w-full lg:space-x-3 text-white hover:text-white focus:bg-indigo-800 dark:focus:bg-indigo-800/60 rounded-md p-2 hover:bg-indigo-800 dark:hover:bg-indigo-800/40 transition-colors ease-in-out"
        >
          <div class="w-8 h-8 rounded-full bg-gray-700 dark:bg-indigo-700 flex items-center justify-center text-white text-sm font-bold flex-shrink-0">
            {{ userInitial }}
          </div>
          <span class="text-sm font-medium flex-1 text-left truncate hidden lg:block">{{ $page.props.auth.user.name }}</span>
          <ChevronRight class="w-4 h-4 text-gray-400 hidden lg:block" />            
        </button>

        <div
          v-if="isProfileOpen"
          @click="isProfileOpen = false"
          class="fixed inset-0 z-40"
        ></div>

        <div
          v-if="isProfileOpen"
          class="absolute bottom-4 left-full ml-2 w-56 bg-white dark:bg-card rounded-lg shadow-xl z-50 border border-gray-200 dark:border-border"
        >
          <div class="flex flex-row px-4 py-3 border-b border-gray-200 dark:border-border">
            <User class="w-5 h-5 text-gray-600 dark:text-gray-400 mt-1" />
            <div class="ml-3 overflow-hidden">
                <p class="text-sm font-medium text-gray-900 dark:text-foreground truncate">{{ $page.props.auth.user.name }}</p>
                <p class="text-xs text-gray-500 dark:text-muted-foreground truncate">{{ $page.props.auth.user.email }}</p>
            </div>
          </div>
          <form @submit.prevent="logout" class="p-2">
            <button
              type="submit"
              class="w-full flex items-center px-4 py-2 font-medium text-sm text-gray-900 dark:text-foreground hover:bg-gray-300 dark:hover:bg-secondary rounded-md transition-colors relative z-10"
            >
             <LogOut class="w-4 h-4 text-red-600" />
             <span class="ml-3">Logout</span>
            </button>
          </form>
        </div>
      </div>
    </nav>

    <div class="flex-1 flex flex-col min-w-0">
      <FlashMessage />

      <!-- Page Heading -->
      <header class="bg-indigo-100 dark:bg-card shadow-sm border-b border-gray-100 dark:border-border text-left" v-if="$slots.header">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
          <slot name="header" />
        </div>
      </header>

      <main class="flex-1 py-8 overflow-y-auto">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <slot />
        </div>
      </main>
    </div>

  </div>
</template>

<script setup>
import { computed, ref } from 'vue'
import { Head, Link, usePage, router } from '@inertiajs/vue3'
import { User, LogOut, ChevronRight, CircleGauge, UserRound, Handshake, Receipt, HandCoins } from 'lucide-vue-next'
import ThemeToggle from '@/Components/ThemeToggle.vue'
import FlashMessage from '@/Components/FlashMessage.vue'

defineProps({
    title: {
        type: String,
        default: 'NexusCRM',
    },
})

const $page = usePage()
const isProfileOpen = ref(false)

const userInitial = computed(() => {
  const name = $page.props.auth.user.name
  return name.charAt(0).toUpperCase()
})

const isActive = (path) => {
  return $page.url === path || $page.url.startsWith(path + '/')
}

const logout = () => {
  // Use the explicit URL path instead of the route() helper
  router.post('/logout')
}
</script>