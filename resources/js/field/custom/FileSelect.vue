<template>
    <div class="flex items-stretch w-full mb-2 relative">
        <input
            type="text"
            class="w-full form-control form-input form-control-bordered mr-4"
            :placeholder="__('Select a file')"
            :value="value"
            :disabled="isReadonly"
        />

        <Button class="whitespace-nowrap nowrap" @click.prevent="openModalFilemanager">
            {{ __('Open FileManager') }}
        </Button>
    </div>
</template>

<script setup>
import { Button } from 'laravel-nova-ui';
const props = defineProps({
    value: {
        type: String,
        default: '',
    },
    field: {
        type: Object,
        required: true,
    },
    isReadonly: {
        type: Boolean,
        default: true,
    },
});

const emit = defineEmits(['open-modal', 'input']);

const openModalFilemanager = () => {
    emit('open-modal');
};
</script>
