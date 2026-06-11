<script setup>
import { Link, useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const form = useForm({
    name:    '',
    email:   '',
    phone:   '',
    company: '',
    address: '',
    status:  'active',
});

function submit() {
    form.post(route('customers.store'));
}
</script>

<template>
    <AppLayout title="New Customer | NexusCRM">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-foreground leading-tight">New Customer</h2>
        </template>

        <div class="py-8">
            <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-card shadow-sm sm:rounded-lg p-6">
                    <form @submit.prevent="submit" class="space-y-5">
                        <!-- Name -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Name <span class="text-red-500">*</span></label>
                            <input
                                v-model="form.name"
                                type="text"
                                class="w-full border-gray-300 dark:border-border dark:bg-secondary dark:text-foreground rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                            />
                            <p v-if="form.errors.name" class="mt-1 text-sm text-red-600">{{ form.errors.name }}</p>
                        </div>

                        <!-- Email -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email <span class="text-red-500">*</span></label>
                            <input
                                v-model="form.email"
                                type="email"
                                class="w-full border-gray-300 dark:border-border dark:bg-secondary dark:text-foreground rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                            />
                            <p v-if="form.errors.email" class="mt-1 text-sm text-red-600">{{ form.errors.email }}</p>
                        </div>

                        <!-- Phone -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Phone</label>
                            <input
                                v-model="form.phone"
                                type="text"
                                class="w-full border-gray-300 dark:border-border dark:bg-secondary dark:text-foreground rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                            />
                            <p v-if="form.errors.phone" class="mt-1 text-sm text-red-600">{{ form.errors.phone }}</p>
                        </div>

                        <!-- Company -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Company</label>
                            <input
                                v-model="form.company"
                                type="text"
                                class="w-full border-gray-300 dark:border-border dark:bg-secondary dark:text-foreground rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                            />
                            <p v-if="form.errors.company" class="mt-1 text-sm text-red-600">{{ form.errors.company }}</p>
                        </div>

                        <!-- Address -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Address</label>
                            <textarea
                                v-model="form.address"
                                rows="3"
                                class="w-full border-gray-300 dark:border-border dark:bg-secondary dark:text-foreground rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                            />
                            <p v-if="form.errors.address" class="mt-1 text-sm text-red-600">{{ form.errors.address }}</p>
                        </div>

                        <!-- Status -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status <span class="text-red-500">*</span></label>
                            <select
                                v-model="form.status"
                                class="w-full border-gray-300 dark:border-border dark:bg-secondary dark:text-foreground rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                            >
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                            <p v-if="form.errors.status" class="mt-1 text-sm text-red-600">{{ form.errors.status }}</p>
                        </div>

                        <!-- Actions -->
                        <div class="flex items-center justify-end gap-3 pt-2">
                            <Link
                                :href="route('customers.index')"
                                class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white"
                            >
                                Cancel
                            </Link>
                            <button
                                type="submit"
                                :disabled="form.processing"
                                class="px-5 py-2 bg-indigo-600 dark:bg-indigo-500 text-white text-sm font-medium rounded-md hover:bg-indigo-700 dark:hover:bg-indigo-400 disabled:opacity-50 transition"
                            >
                                {{ form.processing ? 'Saving...' : 'Save Customer' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AppLayout>
</template>