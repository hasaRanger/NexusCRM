<script setup>
import { usePage } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import { Users, ClipboardCheck, CircleDollarSign, BanknoteArrowUp, User, TrendingUp } from 'lucide-vue-next'
import ShadcnGaugeChart from '@/Components/ShadcnGaugeChart.vue'
import ShadcnPieChart from '@/Components/ShadcnPieChart.vue'
import { Link } from '@inertiajs/vue3'

const $page = usePage()

import { computed } from 'vue'

const props = defineProps({
    stats: {
        type: Object,
        default: () => ({})
    }
})

const invoiceStatusLabels = computed(() => {
    return Object.keys(props.stats.invoice_status_counts || {}).map(status => status.charAt(0).toUpperCase() + status.slice(1));
});

const invoiceStatusSeries = computed(() => {
    return Object.values(props.stats.invoice_status_counts || {});
});

function formatCurrency(amount) {
    return new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'USD',
    }).format(amount);
}
</script>

<template>
    <AppLayout title="Dashboard | NexusCRM">
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard</h2>
            </div>
        </template>

        <!-- Welcome Section -->
        <div class="mb-8">
            <h1 class="text-4xl font-extrabold text-gray-900">
                Welcome back, {{ $page.props.auth.user.name }}!
            </h1>
            <p class="text-gray-600 font-bold mt-2">Here's an overview of your CRM activity.</p>
        </div>

        <!-- Stat Cards Grid -->
        <div class="grid grid-cols-3 lg:grid-cols-4 gap-6 mb-8">
            <div class="col-span-3 bg-gray-200 p-10 rounded-lg">
                <h2 class="text-2xl font-extrabold text-gray-900 mb-1">Sales Pipeline</h2>
                <p class="text-gray-500 text-sm font-medium mb-4">Key metrics across your customer lifecycle.</p>
                <div class="grid grid-cols-2 md:grid-cols-2 lg:grid-cols-4 gap-6 items-center">
                    <!-- Total Customers Card -->
                    <Link href="/customers" class="bg-gray-300 rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow">
                        <div class="flex flex-col justify-between">
                            <div class="w-12 h-12 rounded-lg bg-blue-100 flex items-center justify-center">
                                <Users class="w-6 h-6 text-blue-600" />
                            </div>
                            <div class="space-y-1">
                                <p class="text-3xl font-black text-gray-900 mt-2">{{ stats.customers_count ?? 0 }}</p>
                                <p class="text-gray-600 text-sm font-bold">Total Customers</p>
                                <p class="text-blue-600 text-xs font-light">+10% from last week</p>
                            </div>
                        </div>
                    </Link>

                    <!-- Total Proposals Card -->
                    <Link href="/proposals" class="bg-gray-300 rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow">
                        <div class="flex flex-col justify-between">
                            <div class="w-12 h-12 rounded-lg bg-red-100 flex items-center justify-center">
                                <ClipboardCheck class="w-6 h-6 text-red-600" />
                            </div>
                            <div class="space-y-1">
                                <p class="text-3xl font-black text-gray-900 mt-2">{{ stats.proposals_count ?? 0 }}</p>
                                <p class="text-gray-600 text-sm font-bold">Total Proposals</p>
                                <p class="text-red-600 text-xs font-light">+5% from last week</p>
                            </div>
                        </div>
                    </Link>

                    <!-- Total Invoices Card -->
                    <Link href="/invoices" class="bg-gray-300 rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow">
                        <div class="flex flex-col justify-between">
                            <div class="w-12 h-12 rounded-lg bg-yellow-100 flex items-center justify-center">
                                <CircleDollarSign class="w-6 h-6 text-yellow-600" />
                            </div>
                            <div class="space-y-1">
                                <p class="text-3xl font-black text-gray-900 mt-2">{{ stats.invoices_count ?? 0 }}</p>
                                <p class="text-gray-600 text-sm font-bold">Total Invoices</p>
                                <p class="text-yellow-600 text-xs font-light">+15% from last week</p>
                            </div>
                        </div>
                    </Link>

                    <!-- Total Transactions Card -->
                    <Link href="/transactions" class="bg-gray-300 rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow">
                        <div class="w-12 h-12 rounded-lg bg-green-100 flex items-center justify-center">
                            <BanknoteArrowUp class="w-6 h-6 text-green-600" />
                        </div>
                        <div class="space-y-1">
                            <p class="text-3xl font-black text-gray-900 mt-2">{{ stats.transactions_count ?? 0 }}</p>
                            <p class="text-gray-600 text-sm font-bold">Total Transactions</p>
                            <p class="text-green-600 text-xs font-light">+20% from last week</p>
                        </div>
                    </Link>
                </div>
            </div>

            <!-- Total Revenue Card -->
            <div class="bg-gray-200 p-10 rounded-lg w-72">
                <h2 class="text-2xl font-extrabold text-gray-900 mb-4">Total Revenue</h2>
                <div class="flex items-center gap-2">
                    <p class="text-gray-600 font-bold text-3xl">
                        <span class="text-gray-600 font-bold text-3xl">{{ formatCurrency(stats.transactions_sum_amount
                            ??
                            0)}}</span>
                    </p>
                    <span class="text-gray-600 font-bold text-3xl">/$1K</span>
                </div>
                <!-- Hardcoded for now need to be changed -->
                <div class="flex items-center gap-2 mt-1">
                    <TrendingUp class="w-4 h-4 text-indigo-600" />
                    <p class="text-indigo-600 text-xs font-medium">+3.2% vs last quater</p>
                </div>
                <!-- Semi Circular Progress Bar -->
                <ShadcnGaugeChart height="200" :amount="stats.transactions_sum_amount" :target="1000"/>
            </div>
        </div>

        <div class="grid grid-cols-3 md:grid-cols-1 lg:grid-cols-3 gap-6 mb-8">

            <div class="bg-gray-200 p-10 rounded-lg col-span-2">
                <h2 class="text-2xl font-extrabold text-gray-900 mb-1">Invoice Distribution</h2>
                <p class="text-gray-500 text-sm font-medium mb-4">Breakdown of invoices by status across all customers.
                </p>
                <!-- Pie Chart - Invoice Distribution -->
                 <Link href="/invoices">
                    <ShadcnPieChart height="300" :series="invoiceStatusSeries" :labels="invoiceStatusLabels" />
                 </Link>
            </div>

            <div class="bg-gray-200 p-10 rounded-lg">
                <h2 class="text-2xl font-extrabold text-gray-900">Recent Transactions Feed</h2>
                <p class="text-gray-500 text-sm font-medium mb-4">Latest transactions across your customers.</p>
                <table class="w-full">
                    <tbody class="divide-y divide-gray-100">
                        <Link href="/transactions">
                            <tr v-for="transaction in stats.transactions" :key="transaction.id"
                                class="flex items-center gap-3 p-2 rounded-lg hover:bg-gray-300 cursor-pointer">
                                <td>
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center">
                                            <User class="w-5 h-5 text-blue-600" />
                                        </div>
                                        <span class="font-medium text-xs text-gray-900">{{ transaction.customer.name
                                        }}</span>
                                    </div>
                                </td>
                                <td class="text-right font-bold text-xs text-gray-900">{{ transaction.amount }}</td>
                                <td class="text-right text-gray-500 text-xs">{{ transaction.date }}</td>
                            </tr>
                        </Link>
                    </tbody>
                </table>
            </div>
        </div>

    </AppLayout>
</template>
