<template>
  <Head :title="title" />
  <div class="min-h-screen bg-gray-300 flex">
    
    <nav class="w-64 bg-gray-200 flex flex-col justify-between sticky top-0 h-screen z-20">
      
      <div>
        <div class="h-16 flex items-center justify-start px-6 border-b bg-gray-200">
          <Link href="/dashboard">
          <img src="/logo.jpg" alt="Logo" class="w-12 h-12 rounded-full hover:scale-105 transition-transform duration-200 cursor-pointer ease-in-out">
          </Link>
          <!-- <div class="text-3xl font-extrabold text-gray-900">NexusCRM</div> -->
        </div>
        
        <div class="flex flex-col space-y-1 mt-6 px-3 transition-all ease-in-out">
          <Link
            href="/dashboard"
            :class="[
              isActive('/dashboard')
                ? 'bg-gray-300 text-indigo-700'
                : 'text-gray-600 hover:bg-gray-300 hover:text-gray-900',
              'px-3 py-2 rounded-md text-sm font-extrabold transition-colors flex items-center'
            ]"
          >
          <CircleGauge class="w-4 h-4 mr-1" />
            Dashboard
          </Link>
          <Link
            href="/customers"
            :class="[
              isActive('/customers')
                ? 'bg-gray-300 text-indigo-700'
                : 'text-gray-600 hover:bg-gray-300 hover:text-gray-900',
              'px-3 py-2 rounded-md text-sm font-extrabold transition-colors flex items-center'
            ]"
          >
          <UserRound class="w-4 h-4 mr-1" />
            Customers
          </Link>
          <Link
            href="/proposals"
            :class="[
              isActive('/proposals')
                ? 'bg-gray-300 text-indigo-700'
                : 'text-gray-600 hover:bg-gray-300 hover:text-gray-900',
              'px-3 py-2 rounded-md text-sm font-extrabold transition-colors flex items-center'
            ]"
          >
          <Handshake class="w-4 h-4 mr-1" />
            Proposals
          </Link>
          <Link
            href="/invoices"
            :class="[
              isActive('/invoices')
                ? 'bg-gray-300 text-indigo-700'
                : 'text-gray-600 hover:bg-gray-300 hover:text-gray-900',
              'px-3 py-2 rounded-md text-sm font-extrabold transition-colors flex items-center'
            ]"
          >
          <Receipt class="w-4 h-4 mr-1" />
            Invoices
          </Link>
          <Link
            href="/transactions"
            :class="[
              isActive('/transactions')
                ? 'bg-gray-300 text-indigo-700'
                : 'text-gray-600 hover:bg-gray-300 hover:text-gray-900',
              'px-3 py-2 rounded-md text-sm font-extrabold transition-colors flex items-center'
            ]"
          >
          <HandCoins class="w-4 h-4 mr-1" />
            Transactions
          </Link>
        </div>
      </div>

      <div class="p-4 border-t border-gray-200 relative">
        <button
          @click="isProfileOpen = !isProfileOpen"
          class="flex items-center w-full space-x-3 text-gray-700 hover:text-gray-900 focus:outline-none rounded-md p-2 hover:bg-gray-300 transition-colors"
        >
          <div class="w-8 h-8 rounded-full bg-indigo-600 flex items-center justify-center text-white text-sm font-bold flex-shrink-0">
            {{ userInitial }}
          </div>
          <span class="text-sm font-medium flex-1 text-left truncate">{{ $page.props.auth.user.name }}</span>
          <ChevronRight class="w-4 h-4 text-gray-400" />            
        </button>

        <div
          v-if="isProfileOpen"
          @click="isProfileOpen = false"
          class="fixed inset-0 z-40"
        ></div>

        <div
          v-if="isProfileOpen"
          class="absolute bottom-4 left-full ml-2 w-56 bg-white rounded-lg shadow-xl z-50 border border-gray-200"
        >
          <div class="flex flex-row px-4 py-3 border-b border-gray-200">
            <User class="w-5 h-5 text-gray-600 mt-1" />
            <div class="ml-3 overflow-hidden">
                <p class="text-sm font-medium text-gray-900 truncate">{{ $page.props.auth.user.name }}</p>
                <p class="text-xs text-gray-500 truncate">{{ $page.props.auth.user.email }}</p>
            </div>
          </div>
          <form @submit.prevent="logout" class="p-2">
            <button
              type="submit"
              class="w-full flex items-center px-4 py-2 font-medium text-sm text-gray-900 hover:bg-gray-300 rounded-md transition-colors relative z-10"
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
      <header class="bg-gray-200 shadow-sm border-b border-gray-100 text-left" v-if="$slots.header">
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