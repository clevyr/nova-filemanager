<template>
    <div class="flex items-stretch w-full mb-2 relative">
        <input
            type="text"
            class="w-full mr-4 form-control form-input form-input-bordered"
            :placeholder="__('Select a file')"
            :value="value"
            :disabled="isReadonly"
        />

        <DefaultButton class="whitespace-nowrap nowrap" @click.prevent="openModalFilemanager">
            {{ __('Open FileManager') }}
        </DefaultButton>
    </div>
</template>

<script setup>
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
