<script setup>
import { ref } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import StatusBadge from '@/Components/StatusBadge.vue';
import ConfirmModal from '@/Components/ConfirmModal.vue';
import { Plus, Trash, Wrench } from 'lucide-vue-next';

defineProps({
    proposals: {
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
    router.delete(route('proposals.destroy', confirmingDeleteId.value), {
        onFinish: () => { confirmingDeleteId.value = null; },
    });
}

function changeStatus(proposalId, event) {
    router.patch(route('proposals.changeStatus', proposalId), {
        status: event.target.value,
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
    <AppLayout title="Proposals">
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Proposals</h2>
                <Link
                    :href="route('proposals.create')"
                    class="flex items-center gap-2 px-4 py-2 bg-gray-800 text-white text-sm font-medium rounded-md hover:bg-gray-700 transition"
                >
                    <Plus class="w-4 h-4" />
                    New Proposal
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
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Valid Until</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr v-if="proposals.data.length === 0">
                                    <td colspan="6" class="px-6 py-12 text-center text-gray-500 text-sm">
                                        No proposals found.
                                    </td>
                                </tr>
                                <tr v-for="proposal in proposals.data" :key="proposal.id" class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ proposal.title }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                        {{ proposal.customer?.name ?? '—' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                        {{ formatCurrency(proposal.amount) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <StatusBadge :status="proposal.status" />
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                        {{ formatDate(proposal.valid_until) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm flex items-center gap-4">
                                        <!-- Edit -->
                                        <Link
                                            :href="route('proposals.edit', proposal.id)"
                                            class="text-indigo-600 hover:text-indigo-900 font-medium"
                                        >
                                            <Wrench class="w-4 h-4" />
                                        </Link>
                                        
                                        <!-- Change Status -->
                                        <select
                                            :value="proposal.status"
                                            @change="changeStatus(proposal.id, $event)"
                                            class="text-xs border-gray-300 rounded shadow-sm focus:ring-indigo-500 focus:border-indigo-500 py-1"
                                        >
                                            <option value="draft">Draft</option>
                                            <option value="sent">Sent</option>
                                            <option value="accepted">Accepted</option>
                                            <option value="rejected">Rejected</option>
                                        </select>

                                        <!-- Delete -->
                                        <button
                                            type="button"
                                            class="text-red-600 hover:text-red-900 font-medium"
                                            @click="openDeleteModal(proposal.id)"
                                        >
                                            <Trash class="w-4 h-4" />
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div v-if="proposals.links.length > 3" class="px-6 py-4 border-t border-gray-200 flex flex-wrap gap-1">
                        <template v-for="link in proposals.links" :key="link.label">
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
            message="Are you sure you want to delete this proposal? This action cannot be undone."
            confirm-label="Delete"
            @confirm="confirmDelete"
            @cancel="cancelDelete"
        />
    </AppLayout>
</template>
