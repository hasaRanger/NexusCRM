<script setup>
import { ref } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import StatusBadge from '@/Components/StatusBadge.vue';
import ConfirmModal from '@/Components/ConfirmModal.vue';
import { Plus, Trash, Wrench, Send } from 'lucide-vue-next';

const props = defineProps({
    invoices: {
        type: Object,
        required: true,
    },
    filters: {
        type: Object,
        required: true,
    },
    sort: {
        type: Object,
        default: () => ({
            sort_by: 'created_at',
            sort_direction: 'desc',
        })
    },
});

const search = ref(props.filters.search ?? '');
const statusFilter = ref(props.filters.status ?? 'all');
const sortBy = ref(`${props.sort.sort_by ?? 'created_at'}|${props.sort.sort_direction ?? 'desc'}`);

const confirmingDeleteId = ref(null);
const sendingInvoiceId = ref(null);

function applyFilters() {
    const [sortField, sortDir] = sortBy.value.split('|');
    router.get(route('invoices.index'), {
        search: search.value || undefined,
        status: statusFilter.value !== 'all' ? statusFilter.value : undefined,
        sort_by: sortField,
        sort_direction: sortDir,
    }, {
        preserveState: true,
        replace: true,
    });
}

function openDeleteModal(id) {
    confirmingDeleteId.value = id;
}

function cancelDelete() {
    confirmingDeleteId.value = null;
}

function confirmDelete() {
    router.delete(route('invoices.destroy', confirmingDeleteId.value), {
        onFinish: () => { confirmingDeleteId.value = null; },
    });
}

function changeStatus(invoiceId, event) {
    router.patch(route('invoices.changeStatus', invoiceId), {
        status: event.target.value,
    });
}

function sendInvoice(invoiceId) {
    sendingInvoiceId.value = invoiceId;
    router.post(route('invoices.send', invoiceId), {}, {
        onFinish: () => { sendingInvoiceId.value = null; },
    });
}

function formatCurrency(amount) {
    return new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'USD',
    }).format(amount);
}

function formatDate(dateString) {
    if (!dateString) return '—';
    const date = new Date(dateString);
    // Ignore timezone shift by parsing ISO date parts
    const userTimezoneOffset = date.getTimezoneOffset() * 60000;
    const correctedDate = new Date(date.getTime() + userTimezoneOffset);
    
    return correctedDate.toLocaleDateString('en-US', {
        month: 'short',
        day: 'numeric',
        year: 'numeric',
    });
}
</script>

<template>
    <AppLayout title="Invoices | NexusCRM">
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Invoices</h2>
                
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
                    
                <div class="flex items-center justify-between">
                    <!-- Search and Filter -->
                    <div class="mb-4 flex items-center space-x-4">
                        <input v-model="search" @keyup.enter="applyFilters" placeholder="Search invoices..."
                            class="px-3 py-2 border border-gray-300 rounded-md w-64 text-sm">

                        <select v-model="statusFilter" @change="applyFilters"
                            class="px-3 py-2 border border-gray-300 rounded-md text-sm w-28">
                            <option value="all" selected>All Status</option>
                            <option value="draft">Draft</option>
                            <option value="sent">Sent</option>
                            <option value="paid">Paid</option>
                        </select>

                        <select v-model="sortBy" @change="applyFilters"
                            class="px-3 py-2 border border-gray-300 rounded-md text-sm w-40">
                            <option value="created_at|desc">Newest First</option>
                            <option value="created_at|asc">Oldest First</option>
                            <option value="amount|asc">Amount Low-High</option>
                            <option value="amount|desc">Amount High-Low</option>
                        </select>

                        <button @click="applyFilters"
                            class="px-4 py-2 bg-gray-800 text-white text-sm rounded-md hover:bg-gray-700 transition">
                            Search
                        </button>
                    </div>

                    <!-- Add New Invoice -->
                    <div class="flex justify-end">
                        <Link :href="route('invoices.create')"
                            class="flex items-center gap-2 px-4 py-2 bg-gray-800 text-white text-sm font-medium rounded-md hover:bg-gray-700 transition mb-4">
                            <Plus class="w-4 h-4" />
                            New Invoice
                        </Link>
                    </div>
                </div>
                

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Invoice No.</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tax</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Due Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr v-if="invoices.data.length === 0">
                                    <td colspan="7" class="px-6 py-12 text-center text-gray-500 text-sm">
                                        No invoices found.
                                    </td>
                                </tr>
                                <tr v-for="invoice in invoices.data" :key="invoice.id" class="hover:bg-gray-300">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ invoice.invoice_number }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                        {{ invoice.customer?.name ?? '—' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                        {{ formatCurrency(invoice.amount) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                        {{ formatCurrency(invoice.tax) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                        {{ formatDate(invoice.due_date) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <StatusBadge :status="invoice.status" />
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm flex items-center gap-4">
                                        <!-- Edit -->
                                        <span
                                            v-if="invoice.status === 'paid'"
                                            class="text-gray-300 cursor-not-allowed"
                                            title="Paid invoices cannot be edited"
                                        >
                                            <Wrench class="w-4 h-4" />
                                        </span>
                                        <Link
                                            v-else
                                            :href="route('invoices.edit', invoice.id)"
                                            class="text-indigo-600 hover:text-indigo-900 font-medium"
                                            :title="'Edit'"
                                        >
                                            <Wrench class="w-4 h-4" />
                                        </Link>

                                        <!-- Change Status -->
                                        <select
                                            :value="invoice.status"
                                            @change="changeStatus(invoice.id, $event)"
                                            class="text-xs border-gray-300 rounded shadow-sm focus:ring-indigo-500 focus:border-indigo-500 py-1"
                                        >
                                            <option value="draft">Draft</option>
                                            <option value="sent">Sent</option>
                                            <option value="paid">Paid</option>
                                        </select>

                                        <!-- Send Invoice -->
                                        <button
                                            v-if="invoice.status === 'draft'"
                                            type="button"
                                            :disabled="sendingInvoiceId === invoice.id"
                                            class="text-blue-600 hover:text-blue-900 font-medium disabled:opacity-50 disabled:cursor-wait"
                                            title="Send invoice to customer"
                                            @click="sendInvoice(invoice.id)"
                                        >
                                            <Send class="w-4 h-4" :class="{ 'animate-pulse': sendingInvoiceId === invoice.id }" />
                                        </button>
                                        <span
                                            v-else
                                            class="text-gray-300 cursor-not-allowed"
                                            :title="invoice.status === 'sent' ? 'Already sent' : 'Invoice is paid'"
                                        >
                                            <Send class="w-4 h-4" />
                                        </span>

                                        <!-- Delete -->
                                        <button
                                            type="button"
                                            :disabled="invoice.status === 'paid'"
                                            class="text-red-600 hover:text-red-900 font-medium disabled:text-gray-300 disabled:cursor-not-allowed"
                                            @click="openDeleteModal(invoice.id)"
                                            :title="invoice.status === 'paid' ? 'Paid invoices cannot be deleted' : 'Delete'"
                                        >
                                            <Trash class="w-4 h-4" />
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div v-if="invoices.links.length > 3" class="px-6 py-4 border-t border-gray-200 flex flex-wrap gap-1">
                        <template v-for="link in invoices.links" :key="link.label">
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

        <!-- Confirm Delete Modal -->
        <ConfirmModal
            v-if="confirmingDeleteId !== null"
            message="Are you sure you want to delete this invoice? This action cannot be undone."
            confirm-label="Delete"
            @confirm="confirmDelete"
            @cancel="cancelDelete"
        />
    </AppLayout>
</template>
