<script setup>
defineProps({
    status: {
        type: String,
        required: true,
    },
});

const statusClasses = {
    active:   'bg-green-200 text-green-800',
    inactive: 'bg-gray-300 text-gray-600',
    draft:    'bg-yellow-200 text-yellow-800',
    sent:     'bg-blue-200 text-blue-800',
    paid:     'bg-emerald-200 text-emerald-800',
    accepted: 'bg-teal-200 text-teal-800',
    rejected: 'bg-red-200 text-red-800',
};
</script>

<template>
    <span
        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium capitalize"
        :class="statusClasses[status] ?? 'bg-gray-100 text-gray-600'"
    >
        {{ status }}
    </span>
</template>