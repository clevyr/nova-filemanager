<template>
    <Modal :show="active" @closing="handleClose" @close-via-escape="handleClose">
        <div class="mx-auto bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden">
            <ModalHeader v-text="__('Remove selected?')" />

            <div class="p-6">
                <p>
                    {{ __('Are you sure you want to remove selected files or folders?') }}
                </p>
                <p class="text-sm mt-2">
                    {{ __('Remember: The file and folder and all his contents will be delete from your storage') }}
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
                        type="submit"
                        dusk="confirm-button"
                        :disabled="isDeleting"
                        :loading="isDeleting"
                        state="danger"
                        @click.prevent="deleteData"
                    >
                        {{ __('Delete') }}
                    </Button>
                </div>
            </ModalFooter>
        </div>
    </Modal>
</template>

<script>
    import api from '../api';
    import { Button } from 'laravel-nova-ui';
    export default {
        props: {
            selectedFiles: {
                type: Array,
                required: true,
            },
        },
        components: {
            Button
        },
        data: () => ({
            active: false,
            error: false,
            errorMsg: '',
            isDeleting: false,
        }),

        methods: {
            openModal() {
                this.active = true;
            },

            handleClose() {
                this.active = false;
            },

            async deleteData() {
                this.isDeleting = true;

                for (const file of this.selectedFiles) {
                    if (file.type == 'dir') {
                        await this.deleteFolder(file);
                    } else {
                        await this.deleteFile(file);
                    }
                }

                this.isDeleting = false;
                this.$emit('refresh', true);
                this.handleClose();
            },

            deleteFolder(file) {
                return api.removeDirectory(file.path).then((result) => {
                    this.error = false;
                    if (result == true) {
                        Nova.success(this.__('Deleted successfully'));
                    } else {
                        this.error = true;
                        if (result.error) {
                            this.errorMsg = result.error;
                            Nova.error(this.__('Error:') + ' ' + result.error);
                        } else {
                            Nova.error(this.__('Error deleting. Please, see your logs'));
                        }
                    }
                });
            },

            deleteFile(file) {
                return api.removeFile(file.path).then((result) => {
                    this.error = false;
                    if (result == true) {
                        Nova.success(this.__('Deleted successfully'));
                    } else {
                        this.error = true;
                        if (result.error) {
                            this.errorMsg = result.error;
                            Nova.error(this.__('Error:') + ' ' + result.error);
                        } else {
                            Nova.error(this.__('Error deleting. Please, see your logs'));
                        }
                    }
                });
            },
        },
    };
</script>
