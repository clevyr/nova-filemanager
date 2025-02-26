<template>
    <Modal
        :show="active"
        @closing="handleClose"
        @close-via-escape="handleClose"
    >
        <div class="mx-auto bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden">
            <ModalHeader v-text="__('Deselect File')" />

            <div class="p-6">
                <p>
                    {{ __('Are you sure you want to deselect this file?') }}
                </p>
                <p class="text-sm mt-2">
                    {{ __('Remember: The file will not be delete from your storage') }}
                </p>
            </div>

            <ModalFooter>
                <div class="flex items-center ml-auto">
                    <Button
                        dusk="cancel-action-button"
                        class="ml-auto mr-3"
                        variant="outline"
                        @click.prevent="handleClose"
                    >
                        {{ __('Cancel') }}
                    </Button>

                    <Button
                        ref="confirmButton"
                        state="danger"
                        @click.prevent="handleConfirm"
                    >
                        {{ __('Deselect') }}
                    </Button>
                </div>
            </ModalFooter>
        </div>
    </Modal>
</template>

<script>
    import { Button } from 'laravel-nova-ui';

    export default {
        props: {
            active: {
                default: false,
                required: true,
                type: Boolean,
            },
        },
        components: {
            Button
        },

        mounted() {
            //
        },

        watch: {
            // active(value) {
            //     if (value) {
            //         this.$refs.confirmButton.focus();
            //     }
            // }
        },

        methods: {
            handleClose() {
                this.$emit('close');
            },

            handleConfirm() {
                this.$emit('confirm');
            },
        },
    };
</script>
