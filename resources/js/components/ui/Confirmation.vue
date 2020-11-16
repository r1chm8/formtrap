<template>
    <modal :active="isOpen" @close="close" @escape="close">
        <div class="modal-content max-w-md">
            <div class="bg-white rounded shadow mx-8">
                <div class="content px-6 py-8 text-center">
                    <slot :item="item">
                        Are you sure?
                    </slot>
                </div>

                <div class="button-group">
                    <a
                        class="button"
                        :class="buttonClass"
                        @click="confirm"
                    >{{ buttonText }}</a>

                    <a
                        class="button grey-400 outlined"
                        @click="close"
                    >{{ buttonCancelText }}</a>
                </div>
            </div>
        </div>
    </modal>
</template>

<script>
    import { mapActions, mapGetters } from 'vuex';
    import Modal from './Modal';

    export default {
        props: {
            buttonText: {
                type: String,
                default: 'Confirm'
            },

            buttonClass: {
                type: String,
                default: 'green'
            },

            buttonCancelText: {
                type: String,
                default: 'Cancel'
            },
        },

        components: { Modal },

        computed: {
            ...mapGetters({
                isOpen: 'confirmation/isOpen',
                item: 'confirmation/item'
            })
        },

        methods: {
            ...mapActions({
                close: 'confirmation/close'
            }),

            confirm() {
                this.$emit('confirm', this.item);
                this.close();
            }
        }
    }
</script>
