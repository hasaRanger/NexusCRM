<script setup>
import { Link, useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({
    invoice: {
        type: Object,
        required: true,
    },
    customers: {
        type: Array,
        required: true,
    },
});

const form = useForm({
    customer_id: props.invoice.customer_id,
    amount: props.invoice.amount,
    tax: props.invoice.tax,
    due_date: props.invoice.due_date ?? '',
    status: props.invoice.status,
});

function submit() {
    form.put(route('invoices.update', props.invoice.id));
}
</script>

<template>
    <AppLayout title="Edit Invoice">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit Invoice</h2>
        </template>

        <div class="py-8">
            <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white shadow-sm sm:rounded-lg p-6">
                    <form @submit.prevent="submit" class="space-y-5">
                        <!-- Invoice Number (Read Only) -->
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Invoice Number</label>
                            <div class="w-full px-3 py-2 bg-gray-100 border border-gray-350 rounded-md text-gray-700 select-none">
                                {{ invoice.invoice_number }}
                            </div>
                        </div>

                        <!-- Customer -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Customer <span class="text-red-500">*</span></label>
                            <select
                                v-model="form.customer_id"
                                class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                            >
                                <option value="" disabled>Select a customer</option>
                                <option
                                    v-for="customer in customers"
                                    :key="customer.id"
                                    :value="customer.id"
                                >
                                    {{ customer.name }}
                                </option>
                            </select>
                            <p v-if="form.errors.customer_id" class="mt-1 text-sm text-red-650">{{ form.errors.customer_id }}</p>
                        </div>

                        <!-- Amount -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Amount ($) <span class="text-red-500">*</span></label>
                            <input
                                v-model="form.amount"
                                type="number"
                                step="0.01"
                                min="0"
                                class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                placeholder="0.00"
                            />
                            <p v-if="form.errors.amount" class="mt-1 text-sm text-red-650">{{ form.errors.amount }}</p>
                        </div>

                        <!-- Tax -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tax ($)</label>
                            <input
                                v-model="form.tax"
                                type="number"
                                step="0.01"
                                min="0"
                                class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                placeholder="0.00"
                            />
                            <p v-if="form.errors.tax" class="mt-1 text-sm text-red-650">{{ form.errors.tax }}</p>
                        </div>

                        <!-- Due Date -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Due Date</label>
                            <input
                                v-model="form.due_date"
                                type="date"
                                class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                            />
                            <p v-if="form.errors.due_date" class="mt-1 text-sm text-red-655">{{ form.errors.due_date }}</p>
                        </div>

                        <!-- Status -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Status <span class="text-red-500">*</span></label>
                            <select
                                v-model="form.status"
                                class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                            >
                                <option value="draft">Draft</option>
                                <option value="sent">Sent</option>
                                <option value="paid">Paid</option>
                            </select>
                            <p v-if="form.errors.status" class="mt-1 text-sm text-red-650">{{ form.errors.status }}</p>
                        </div>

                        <!-- Actions -->
                        <div class="flex items-center justify-end gap-3 pt-2">
                            <Link
                                :href="route('invoices.index')"
                                class="text-sm text-gray-600 hover:text-gray-900"
                            >
                                Cancel
                            </Link>
                            <button
                                type="submit"
                                :disabled="form.processing"
                                class="px-5 py-2 bg-gray-800 text-white text-sm font-medium rounded-md hover:bg-gray-700 disabled:opacity-50 transition"
                            >
                                {{ form.processing ? 'Updating...' : 'Update Invoice' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
