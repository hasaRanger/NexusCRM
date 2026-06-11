<script setup>
import { ref } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import StatusBadge from '@/Components/StatusBadge.vue';
import ConfirmModal from '@/Components/ConfirmModal.vue';
import { Plus, Trash, Wrench } from 'lucide-vue-next';

const props = defineProps({
    proposals: {
        type: Object,
        required: true,
    },
    filters: {
        type: Object,
        default: () => ({
            search: '',
            status: 'all',
        }),
    },
    sort: {
        type: Object,
        default: () => ({
            sort_by: 'created_at',
            sort_direction: 'desc',
        }),
    },
});

// Reactive state initialized from server-provided filters
const search = ref(props.filters.search ?? '');
const statusFilter = ref(props.filters.status ?? 'all');
const sortBy = ref(props.sort.sort_by + '|' + props.sort.sort_direction);

function applyFilters() {
    const [sortField, sortDir] = sortBy.value.split('|');

    router.get(route('proposals.index'), {
        search: search.value || undefined,
        status: statusFilter.value !== 'all' ? statusFilter.value : undefined,
        sort_by: sortField,
        sort_direction: sortDir,
    }, {
        preserveState: true,
        replace: true,
    });
}

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
    <AppLayout title="Proposals | NexusCRM">
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="font-semibold text-xl text-gray-800 dark:text-foreground leading-tight">Proposals</h2>
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
                        <input v-model="search" @keyup.enter="applyFilters" placeholder="Search proposals..."
                            class="px-3 py-2 border border-gray-300 dark:border-border dark:bg-secondary dark:text-foreground rounded-md w-64 text-sm">

                        <select v-model="statusFilter" @change="applyFilters"
                            class="px-3 py-2 border border-gray-300 dark:border-border dark:bg-secondary dark:text-foreground rounded-md text-sm w-28">
                            <option value="all">All Status</option>
                            <option value="draft">Draft</option>
                            <option value="sent">Sent</option>
                            <option value="accepted">Accepted</option>
                            <option value="rejected">Rejected</option>
                        </select>

                        <select v-model="sortBy" @change="applyFilters"
                            class="px-3 py-2 border border-gray-300 dark:border-border dark:bg-secondary dark:text-foreground rounded-md text-sm w-32">
                            <option value="created_at|desc">Newest First</option>
                            <option value="created_at|asc">Oldest First</option>
                            <option value="title|asc">Title A-Z</option>
                            <option value="title|desc">Title Z-A</option>
                        </select>

                        <button @click="applyFilters"
                            class="px-4 py-2 bg-indigo-600 dark:bg-indigo-500 text-white text-sm rounded-md hover:bg-indigo-700 dark:hover:bg-indigo-400 transition">
                            Search
                        </button>
                    </div>

                    <!-- Add New Proposal -->
                    <div class="flex justify-end mb-4">
                        <Link :href="route('proposals.create')"
                            class="flex items-center gap-2 px-4 py-2 bg-indigo-600 dark:bg-indigo-500 text-white text-sm font-medium rounded-md hover:bg-indigo-700 dark:hover:bg-indigo-400 transition">
                            <Plus class="w-4 h-4" />
                            New Proposal
                        </Link>
                    </div>
                </div>

                <div class="bg-white dark:bg-card overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-border">
                            <thead class="bg-gray-50 dark:bg-secondary">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-muted-foreground uppercase tracking-wider">Title</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-muted-foreground uppercase tracking-wider">Customer</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-muted-foreground uppercase tracking-wider">Amount</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-muted-foreground uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-muted-foreground uppercase tracking-wider">Valid Until</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-muted-foreground uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-card divide-y divide-gray-200 dark:divide-border">
                                <tr v-if="proposals.data.length === 0">
                                    <td colspan="6" class="px-6 py-12 text-center text-gray-500 dark:text-muted-foreground text-sm">
                                        No proposals found.
                                    </td>
                                </tr>
                                <tr v-for="proposal in proposals.data" :key="proposal.id" class="hover:bg-gray-50 dark:hover:bg-accent">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-foreground">
                                        {{ proposal.title }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-muted-foreground min-w-[120px] max-w-[200px] overflow-hidden text-ellipsis"
                                        :title="proposal.customer?.name ?? ''">
                                        {{ proposal.customer?.name ?? '—' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-muted-foreground">
                                        {{ formatCurrency(proposal.amount) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <StatusBadge :status="proposal.status" />
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-muted-foreground">
                                        {{ formatDate(proposal.valid_until) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm flex items-center gap-4">
                                        <!-- Edit -->
                                        <Link
                                            :href="route('proposals.edit', proposal.id)"
                                            class="text-indigo-600 hover:text-indigo-900 font-medium"
                                            :title="'Edit'"
                                        >
                                            <Wrench class="w-4 h-4" />
                                        </Link>
                                        
                                        <!-- Change Status -->
                                        <select
                                            :value="proposal.status"
                                            @change="changeStatus(proposal.id, $event)"
                                            class="text-xs border-gray-300 dark:border-border dark:bg-secondary dark:text-foreground rounded shadow-sm focus:ring-indigo-500 focus:border-indigo-500 py-1"
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
                                            :title="'Delete'"
                                        >
                                            <Trash class="w-4 h-4" />
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div v-if="proposals.links.length > 3" class="px-6 py-4 border-t border-gray-200 dark:border-border flex flex-wrap gap-1">
                        <template v-for="link in proposals.links" :key="link.label">
                            <Link
                                v-if="link.url"
                                :href="link.url"
                                class="px-3 py-1 text-sm rounded border"
                                :class="link.active
                                    ? 'bg-indigo-600 dark:bg-indigo-500 text-white border-indigo-600 dark:border-indigo-500'
                                    : 'bg-white dark:bg-secondary text-gray-700 dark:text-gray-300 border-gray-300 dark:border-border hover:bg-gray-50 dark:hover:bg-accent'"
                                v-html="link.label"
                            />
                            <span
                                v-else
                                class="px-3 py-1 text-sm rounded border border-gray-200 dark:border-border text-gray-400 dark:text-gray-500 cursor-not-allowed"
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
