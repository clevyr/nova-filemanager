<template>
    <portal to="portal-filemanager" name="Confirm Delete" transition="fade-transition">
        <modal v-if="active" @modal-close="handleClose">
            <div
                class="bg-white rounded-lg shadow-lg overflow-hidden"
                style="width: 460px"
            >
                <div class="p-8">
                    <heading :level="2" class="mb-6">
                        <template v-if="type == 'folder'">
                            {{ __('Remove folder') }}: {{ name }}
                        </template>
                        <template v-else>
                            {{ __('Remove file') }}: {{ name }}
                        </template>
                    </heading>
                    <template v-if="type == 'folder'">
                        <p class="text-80">{{__('Are you sure you want to remove this folder?')}}</p>
                        <p class="text-sm text-80 mt-2">{{ __('Remember: The folder and all his contents will be delete from your storage') }}</p>
                    </template>
                    <template v-else>
                        <p class="text-80">{{__('Are you sure you want to remove this file?')}}</p>
                        <p class="text-sm text-80 mt-2">{{ __('Remember: The file will be delete from your storage') }}</p>
                    </template>

                </div>

                <div class="bg-30 px-6 py-3 flex">
                    <div class="ml-auto">
                        <Button
                            dusk="cancel-upload-delete-button"
                            variant="link"
                            data-testid="cancel-button"
                            @click.prevent="handleClose"
                            class="mr-3">
                            {{__('Cancel')}}
                        </Button>

                        <Button
                            ref="confirmButton"
                            data-testid="confirm-button"
                            :disabled="isDeleting"
                            :loading="isDeleting"
                            state="danger"
                            @click.prevent="deleteData"
                        >
                            {{ __('Delete') }}
                        </Button>
                    </div>
                </div>
            </div>
        </modal>
    </portal>
</template>

<script>
import api from '../api';
import { Button } from 'laravel-nova-ui'

export default {
    components: {
        Button
    },
    data: () => ({
        active: false,
        name: null,
        type: null,
        path: null,
        error: false,
        errorMsg: '',
        isDeleting: false,
    }),

    methods: {
        openModal(type, path) {
            this.type = type;
            this.path = path;
            this.name = path.replace(/^.*[\\/]/, '');
            this.active = true;
        },

        handleClose() {
            this.active = false;
        },

        deleteData() {
            if (this.type == 'folder') {
                this.deleteFolder();
            } else {
                this.deleteFile();
            }
        },

        deleteFolder() {
            return api.removeDirectory(this.path).then(result => {
                this.error = false;
                this.name = null;
                if (result == true) {
                    this.$toasted.show(this.__('Deleted successfully'), { type: 'success' });
                    this.$emit('refresh', true);
                    this.handleClose();
                } else {
                    this.error = true;
                    if (result.error) {
                        this.errorMsg = result.error;
                        this.$toasted.show(this.__('Error:') + ' ' + result.error, {
                            type: 'error',
                        });
                    } else {
                        this.$toasted.show(this.__('Error deleting. Please, see your logs'), {
                            type: 'error',
                        });
                    }
                }
            });
        },

        deleteFile() {
            return api.removeFile(this.path).then(result => {
                this.error = false;
                this.name = null;
                if (result == true) {
                    this.$toasted.show(this.__('Deleted successfully'), { type: 'success' });
                    this.$emit('refresh', true);
                    this.handleClose();
                } else {
                    this.error = true;
                    if (result.error) {
                        this.errorMsg = result.error;
                        this.$toasted.show(this.__('Error:') + ' ' + result.error, {
                            type: 'error',
                        });
                    } else {
                        this.$toasted.show(this.__('Error deleting. Please, see your logs'), {
                            type: 'error',
                        });
                    }
                }
            });
        },
    },
};
</script>
