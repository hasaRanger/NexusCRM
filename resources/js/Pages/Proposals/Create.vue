<script setup>
import { Link, useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

defineProps({
    customers: {
        type: Array,
        required: true,
    },
});

const form = useForm({
    customer_id: '',
    title: '',
    description: '',
    amount: '',
    status: 'draft',
    valid_until: '',
});

function submit() {
    form.post(route('proposals.store'));
}
</script>

<template>
    <AppLayout title="New Proposal | NexusCRM">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">New Proposal</h2>
        </template>

        <div class="py-8">
            <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white shadow-sm sm:rounded-lg p-6">
                    <form @submit.prevent="submit" class="space-y-5">
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
                            <p v-if="form.errors.customer_id" class="mt-1 text-sm text-red-600">{{ form.errors.customer_id }}</p>
                        </div>

                        <!-- Title -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Title <span class="text-red-500">*</span></label>
                            <input
                                v-model="form.title"
                                type="text"
                                class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                placeholder="Enter proposal title"
                            />
                            <p v-if="form.errors.title" class="mt-1 text-sm text-red-600">{{ form.errors.title }}</p>
                        </div>

                        <!-- Description -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                            <textarea
                                v-model="form.description"
                                rows="3"
                                class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                placeholder="Enter description (optional)"
                            />
                            <p v-if="form.errors.description" class="mt-1 text-sm text-red-600">{{ form.errors.description }}</p>
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
                            <p v-if="form.errors.amount" class="mt-1 text-sm text-red-600">{{ form.errors.amount }}</p>
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
                                <option value="accepted">Accepted</option>
                                <option value="rejected">Rejected</option>
                            </select>
                            <p v-if="form.errors.status" class="mt-1 text-sm text-red-600">{{ form.errors.status }}</p>
                        </div>

                        <!-- Valid Until -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Valid Until</label>
                            <input
                                v-model="form.valid_until"
                                type="date"
                                class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                            />
                            <p v-if="form.errors.valid_until" class="mt-1 text-sm text-red-600">{{ form.errors.valid_until }}</p>
                        </div>

                        <!-- Actions -->
                        <div class="flex items-center justify-end gap-3 pt-2">
                            <Link
                                :href="route('proposals.index')"
                                class="text-sm text-gray-600 hover:text-gray-900"
                            >
                                Cancel
                            </Link>
                            <button
                                type="submit"
                                :disabled="form.processing"
                                class="px-5 py-2 bg-gray-800 text-white text-sm font-medium rounded-md hover:bg-gray-700 disabled:opacity-50 transition"
                            >
                                {{ form.processing ? 'Saving...' : 'Save Proposal' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
