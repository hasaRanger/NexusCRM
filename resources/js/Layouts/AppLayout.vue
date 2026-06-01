<template>
  <div class="min-h-screen bg-gray-100">
    <!-- Flash Messages -->
    <FlashMessage />

    <!-- Navigation Bar -->
    <nav class="bg-white shadow-sm">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
          <!-- Left side - Logo and Links -->
          <div class="flex items-center space-x-10">
            <div class="text-3xl font-extrabold text-gray-900">CRM</div>
            <div class="hidden md:flex space-x-1 ml-10">
              <Link
                href="/dashboard"
                :class="[
                  isActive('/dashboard')
                    ? 'text-indigo-600 border-b-2 border-indigo-600'
                    : 'text-gray-600 hover:text-gray-900 border-b-2 border-transparent',
                  'px-3 py-2 text-sm font-medium transition-colors'
                ]"
              >
                Dashboard
              </Link>
              <Link
                href="/customers"
                :class="[
                  isActive('/customers')
                    ? 'text-indigo-600 border-b-2 border-indigo-600'
                    : 'text-gray-600 hover:text-gray-900 border-b-2 border-transparent',
                  'px-3 py-2 text-sm font-medium transition-colors'
                ]"
              >
                Customers
              </Link>
              <Link
                href="/proposals"
                :class="[
                  isActive('/proposals')
                    ? 'text-indigo-600 border-b-2 border-indigo-600'
                    : 'text-gray-600 hover:text-gray-900 border-b-2 border-transparent',
                  'px-3 py-2 text-sm font-medium transition-colors'
                ]"
              >
                Proposals
              </Link>
              <Link
                href="/invoices"
                :class="[
                  isActive('/invoices')
                    ? 'text-indigo-600 border-b-2 border-indigo-600'
                    : 'text-gray-600 hover:text-gray-900 border-b-2 border-transparent',
                  'px-3 py-2 text-sm font-medium transition-colors'
                ]"
              >
                Invoices
              </Link>
              <Link
                href="/transactions"
                :class="[
                  isActive('/transactions')
                    ? 'text-indigo-600 border-b-2 border-indigo-600'
                    : 'text-gray-600 hover:text-gray-900 border-b-2 border-transparent',
                  'px-3 py-2 text-sm font-medium transition-colors'
                ]"
              >
                Transactions
              </Link>
            </div>
          </div>

          <!-- Right side - Profile Dropdown -->
          <div class="relative">
            <button
              @click="isProfileOpen = !isProfileOpen"
              class="flex items-center space-x-2 text-gray-700 hover:text-gray-900 focus:outline-none"
            >
              <div class="w-8 h-8 rounded-full bg-indigo-600 flex items-center justify-center text-white text-sm font-bold">
                {{ userInitial }}
              </div>
              <span class="text-sm font-medium hidden sm:inline">{{ $page.props.auth.user.name }}</span>
            <ChevronDown class="w-4 h-4 text-gray-600" />            
            </button>

            <!-- Dropdown Menu -->
            <Teleport to="body">
              <div
                v-if="isProfileOpen"
                @click="isProfileOpen = false"
                class="fixed inset-0 z-40"
              />
            </Teleport>
            <div
              v-if="isProfileOpen"
              class="absolute right-0 mt-2 w-52 bg-white rounded-lg shadow-lg z-50 border border-gray-200"
            >
              <div class="flex flex-row px-4 py-3 border-b border-gray-200">
                <User class="w-5 h-5 text-gray-400 mt-1" />
                <div class="ml-3">
                    <p class="text-sm font-medium text-gray-900">{{ $page.props.auth.user.name }}</p>
                    <p class="text-xs text-gray-500">{{ $page.props.auth.user.email }}</p>
                </div>
              </div>
              <form @submit.prevent="logout" class="p-2">
                <button
                  type="submit"
                  class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-md transition-colors"
                >
                 <LogOut class="w-4 h-4 inline-block text-red-600" />
                 <span class="ml-2">Logout</span>
                </button>
              </form>
            </div>
          </div>
        </div>
      </div>
    </nav>

    <!-- Page Content -->
    <main class="py-6">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <slot />
      </div>
    </main>
  </div>
</template>

<script setup>
import { computed, ref } from 'vue'
import { Link, useForm, usePage } from '@inertiajs/vue3'
import { User, LogOut, ChevronDown, User2 } from 'lucide-vue-next'
import FlashMessage from '@/Components/FlashMessage.vue'

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
  useForm({}).post('/logout')
}
</script>