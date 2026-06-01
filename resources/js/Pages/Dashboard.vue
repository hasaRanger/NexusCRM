<script setup>
import { usePage } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import {Users, ClipboardCheck, CircleDollarSign, BanknoteArrowUp, User} from 'lucide-vue-next'

const $page = usePage()
</script>

<template>
  <AppLayout>
    <!-- Welcome Section -->
    <div class="mb-8">
      <h1 class="text-3xl font-bold text-gray-900">
        Welcome back, {{ $page.props.auth.user.name }}!
      </h1>
      <p class="text-gray-600 mt-2">Here's an overview of your CRM activity.</p>
    </div>

    <!-- Stat Cards Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
      <!-- Total Customers Card -->
      <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-gray-600 text-sm font-medium">Total Customers</p>
            <p class="text-3xl font-bold text-gray-900 mt-2">0</p>
          </div>
          <div class="w-12 h-12 rounded-lg bg-blue-100 flex items-center justify-center">
            <Users class="w-6 h-6 text-blue-600" />
          </div>
        </div>
      </div>

      <!-- Total Proposals Card -->
      <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-gray-600 text-sm font-medium">Total Proposals</p>
            <p class="text-3xl font-bold text-gray-900 mt-2">0</p>
          </div>
          <div class="w-12 h-12 rounded-lg bg-red-100 flex items-center justify-center">
            <ClipboardCheck class="w-6 h-6 text-red-600" />
          </div>
        </div>
      </div>

      <!-- Total Invoices Card -->
      <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-gray-600 text-sm font-medium">Total Invoices</p>
            <p class="text-3xl font-bold text-gray-900 mt-2">0</p>
          </div>
          <div class="w-12 h-12 rounded-lg bg-yellow-100 flex items-center justify-center">
            <CircleDollarSign class="w-6 h-6 text-yellow-600" />
          </div>
        </div>
      </div>

      <!-- Total Transactions Card -->
      <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-gray-600 text-sm font-medium">Total Transactions</p>
            <p class="text-3xl font-bold text-gray-900 mt-2">0</p>
          </div>
          <div class="w-12 h-12 rounded-lg bg-green-100 flex items-center justify-center">
            <BanknoteArrowUp class="w-6 h-6 text-green-600" />
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>
