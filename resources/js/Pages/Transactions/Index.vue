<script setup>
import { Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import { ArrowLeftRight } from 'lucide-vue-next';

defineProps({
    transactions: {
        type: Object,
        required: true,
    },
});

function formatCurrency(amount) {
    return new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'USD',
    }).format(amount);
}

function formatDate(dateString) {
    if (!dateString) return '—';
    const date = new Date(dateString);
    return date.toLocaleDateString('en-US', {
        month: 'short',
        day: 'numeric',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
    });
}
</script>

<template>
    <AppLayout title="Transactions">
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Transactions</h2>
            </div>
        </template>

        <div class="py-8">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Flash message -->
                <div
                    v-if="$page.props.flash?.success"
                    class="mb-4 p-4 bg-green-100 text-green-800 rounded-md text-sm"
                >
                    {{ $page.props.flash.success }}
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Invoice No.</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Gateway</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reference</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Paid At</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr v-if="transactions.data.length === 0">
                                    <td colspan="6" class="px-6 py-12 text-center text-gray-500 text-sm">
                                        <div class="flex flex-col items-center gap-2">
                                            <ArrowLeftRight class="w-8 h-8 text-gray-300" />
                                            <span>No transactions yet.</span>
                                        </div>
                                    </td>
                                </tr>
                                <tr v-for="transaction in transactions.data" :key="transaction.id" class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ transaction.invoice?.invoice_number ?? '—' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                        {{ transaction.invoice?.customer?.name ?? '—' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                        {{ formatCurrency(transaction.amount) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800 capitalize">
                                            {{ transaction.gateway }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 font-mono text-xs">
                                        {{ transaction.reference ? transaction.reference.substring(0, 24) + '…' : '—' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                        {{ formatDate(transaction.paid_at) }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div v-if="transactions.links.length > 3" class="px-6 py-4 border-t border-gray-200 flex flex-wrap gap-1">
                        <template v-for="link in transactions.links" :key="link.label">
                            <Link
                                v-if="link.url"
                                :href="link.url"
                                class="px-3 py-1 text-sm rounded border"
                                :class="link.active
                                    ? 'bg-gray-800 text-white border-gray-800'
                                    : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-50'"
                                v-html="link.label"
                            />
                            <span
                                v-else
                                class="px-3 py-1 text-sm rounded border border-gray-200 text-gray-400 cursor-not-allowed"
                                v-html="link.label"
                            />
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
