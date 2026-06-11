<script setup>
import Modal from '@/Components/Modal.vue';
import DangerButton from '@/Components/DangerButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';

defineProps({
    message: {
        type: String,
        required: true,
    },
    confirmLabel: {
        type: String,
        default: 'Delete',
    },
});

defineEmits(['confirm', 'cancel']);
</script>

<template>
    <Modal :show="true" @close="$emit('cancel')" maxWidth="md">
        <div class="p-6">
            <h2 class="text-lg font-medium text-gray-900 dark:text-foreground">
                Confirm Action
            </h2>

            <p class="mt-3 text-sm text-gray-600 dark:text-muted-foreground">
                {{ message }}
            </p>

            <div class="mt-6 flex justify-end gap-3">
                <SecondaryButton @click="$emit('cancel')">
                    Cancel
                </SecondaryButton>

                <DangerButton @click="$emit('confirm')">
                    {{ confirmLabel }}
                </DangerButton>
            </div>
        </div>
    </Modal>
</template>