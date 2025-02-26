<template>
    <component
        ref="button"
        v-bind="{ size, align, state, ...$attrs }"
        :is="component"
        :disabled="stepsComplete"
        @click="incrementStep()"
    >
        <slot name="start" v-if="currentStep === 0" />
        <slot name="finish" v-if="stepsComplete" />
        {{ currentMessage }}
    </component>
</template>

<script>
import { Button } from 'laravel-nova-ui';

export default {
    props: {
        size: {
            type: String,
            default: 'large',
        },
        state: {
            type: String,
            default: 'default',
        },
        align: {
            type: String,
            default: 'center',
            validator: v => ['left', 'center'].includes(v),
        },
        component: {
            type: String,
            default: 'Button',
        },
        messages: {
            type: Array,
            default: [],
        },
    },
    components: {
        Button,
    },
    data() {
        return {
            defaultSteps: [
                this.__('Click to confirm'),
                this.__('Are you sure?'),
                this.__('✔'),
            ],
            currentStep: 0,
        };
    },
    computed: {
        messageList() {
            return this.messages ? this.messages : this.defaultSteps;
        },
        currentMessage() {
            return this.messageList[this.currentStep];
        },
        lastMessageIndex() {
            return this.messageList.length - 1;
        },
        stepsComplete() {
            return this.currentStep === this.lastMessageIndex;
        },
    },
    methods: {
        incrementStep() {
            this.currentStep++;
            if (this.stepsComplete) {
                this.$emit('success');
            } else {
                this.$emit('incremented');
            }
            setTimeout(() => this.currentStep = 0, 3000);
        },
        reset() {
            this.currentStep = 0;
            this.$emit('reset');
        },
    },
};
</script>
