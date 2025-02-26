<template>
    <portal to="modals" name="Deselect File" transition="fade-transition">
        <modal v-if="active" @modal-close="handleClose">
            <div
                class="bg-white rounded-lg shadow-lg overflow-hidden"
                style="width: 460px"
            >
                <div class="p-8">
                    <heading :level="2" class="mb-6">{{__('Deselect File')}}</heading>
                    <p class="text-80">{{__('Are you sure you want to deselect this file?')}}</p>
                    <p class="text-sm text-80 mt-2">{{ __('Remember: The file will not be delete from your storage') }}</p>
                </div>

                <div class="bg-30 px-6 py-3 flex">
                    <div class="ml-auto">
                        <Button
                            dusk="cancel-upload-delete-button"
                            variant="link"
                            data-testid="cancel-button"
                            @click.prevent="handleClose"
                        >
                            {{__('Cancel')}}
                        </Button>

                        <button
                            dusk="confirm-upload-delete-button"
                            ref="confirmButton"
                            data-testid="confirm-button"
                            state="danger"
                            @click.prevent="handleConfirm">
                            {{__('Deselect')}}
                        </button>
                    </div>
                </div>
            </div>
        </modal>
    </portal>
</template>

<script>
import { Button } from 'laravel-nova-ui'

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
