<script setup>
import { ref } from 'vue';
import { Link, router, usePage } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import StatusBadge from '@/Components/StatusBadge.vue';
import ConfirmModal from '@/Components/ConfirmModal.vue';
import {Plus ,Trash, Wrench, ShieldCheck, ShieldBan} from "lucide-vue-next";

defineProps({
    customers: {
        type: Object,
        required: true,
    },
});

const confirmingDeleteId = ref(null);

function openDeleteModal(id) {
    confirmingDeleteId.value = id;
}

function cancelDelete() {
    confirmingDeleteId.value = null;
}

function confirmDelete() {
    router.delete(route('customers.destroy', confirmingDeleteId.value), {
        onFinish: () => { confirmingDeleteId.value = null; },
    });
}

function toggleStatus(customer) {
    router.patch(route('customers.toggleStatus', customer.id));
}
</script>

<template>
    <AppLayout title="Customers">
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Customers</h2>
                <Link
                    :href="route('customers.create')"
                    class="flex items-center gap-2 px-4 py-2 bg-gray-800 text-white text-sm font-medium rounded-md hover:bg-gray-700 transition"
                >
                    <Plus class="w-5 h-5" />
                    New Customer
                </Link>
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
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Phone</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Company</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr v-if="customers.data.length === 0">
                                    <td colspan="6" class="px-6 py-12 text-center text-gray-500 text-sm">
                                        No customers found.
                                    </td>
                                </tr>
                                <tr v-for="customer in customers.data" :key="customer.id" class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ customer.name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                        {{ customer.email }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                        {{ customer.phone ?? '—' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                        {{ customer.company ?? '—' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <StatusBadge :status="customer.status" />
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm space-x-5 flex items-center">
                                        <Link
                                            :href="route('customers.edit', customer.id)"
                                            class="text-indigo-600 hover:text-indigo-900 font-medium"
                                        >
                                            <Wrench class="w-4 h-4" />
                                        </Link>
                                        <button
                                            type="button"
                                            class="font-medium"
                                            @click="toggleStatus(customer)"
                                        >
                                            <ShieldBan v-if="customer.status === 'active'" class="text-yellow-600 hover:text-yellow-900 w-4 h-4" />
                                            <ShieldCheck v-else class="text-green-600 hover:text-green-900 w-4 h-4" />
                                        </button>
                                        <button
                                            type="button"
                                            class="text-red-600 hover:text-red-900 font-medium"
                                            @click="openDeleteModal(customer.id)"
                                        >
                                            <Trash class="w-4 h-4" />
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div v-if="customers.links.length > 3" class="px-6 py-4 border-t border-gray-200 flex flex-wrap gap-1">
                        <template v-for="link in customers.links" :key="link.label">
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
            message="Are you sure you want to delete this customer? This action cannot be undone."
            confirm-label="Delete"
            @confirm="confirmDelete"
            @cancel="cancelDelete"
        />
    </AppLayout>
</template>