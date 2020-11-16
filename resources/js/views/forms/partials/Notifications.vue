<template>
    <div>
        <a @click="open" title="Form notifications">
            <span
                class="icon w-10 h-10 rounded hover:bg-grey-200"
                :class="{ 'text-grey-500 hover:text-grey-600': ! formData.notification_email }"
            >
                <icon icon="bell"></icon>
            </span>
        </a>

        <modal :active="active" @close="close" @escape="close">
            <div class="modal-content max-w-2xl">
                <div class="bg-white rounded shadow mx-4">
                    <div class="flex items-center justify-between bg-purple-200 p-4 rounded-t md:px-8">
                        <h2 class="title">
                            Form Notifications
                        </h2>

                        <a class="icon text-grey-600 -mr-2" @click="cancel">
                            <icon icon="times"></icon>
                        </a>
                    </div>

                    <form @submit.prevent="submit" class="px-4 pt-6 pb-8 md:px-8">
                        <form-field input="notification_email" label="Notification email">
                            <vue-input
                                id="notification_email"
                                ref="input"
                                v-model="form.notification_email"
                                autofocus
                                @keypress.enter="submit"
                            ></vue-input>
                        </form-field>

                        <form-field>
                            <p>
                                Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                                Mauris eu condimentum turpis.
                            </p>   
                        </form-field>

                        <form-field>
                            <button
                                class="button"
                                :class="{ 'loading': isProcessing }"
                                :disabled="isProcessing"
                            >Update</button>

                            <button
                                v-if="formData.notification_email"
                                class="button grey-400 outlined ml-2"
                                :class="{ 'loading': isProcessing }"
                                :disabled="isProcessing"
                                @click="disable"
                            >Disable notifications</button>
                        </form-field>
                    </form>
                </div>
            </div>
        </modal>
    </div>
</template>

<script>
    import { mapGetters, mapMutations, mapActions } from 'vuex';

    import FormField from '@js/components/form/Field';
    import Modal from '@js/components/ui/Modal';
    import VueInput from '@js/components/form/Input';

    export default {
        components: {
            FormField,
            Modal,
            VueInput
        },

        data() {
            return {
                active: false,

                form: {
                    notification_email: ''
                },

                isProcessing: false
            }
        },

        computed: {
            ...mapGetters({
                formData: 'form/data',
                hasError: 'errors/contains'
            })
        },

        methods: {
            ...mapActions({
                setErrors: 'errors/set',
                clearErrors: 'errors/clear'
            }),

            ...mapMutations({
                setFormData: 'form/setData'
            }),

            open() {
                this.form.notification_email = this.formData.notification_email;
                this.active = true;

                this.$nextTick(() => {
                    this.$refs.input.$el.focus();
                });
            },

            submit() {
                if (this.form.notification_email === this.formData.notification_email) {
                    return this.close();
                }
                
                if (! this.isProcessing) {
                    this.isProcessing = true;

                    axios.patch(`/api/forms/${this.formData.id}`, this.form)
                        .then(response => {
                            this.clearErrors();
                            this.setFormData(response.data.data);

                            if (this.form.notification_email) {
                                this.close();

                                this.$toasted.success('Notification email address successfully updated.', {
                                    duration: 3000
                                });
                            }
                        })
                        .catch(error => {
                            if (error.response.status === 422) {
                                this.setErrors(error.response.data.errors);
                            } else {
                                this.$toasted.error('An unexpected error occured.', {
                                    duration: 3000
                                });
                            }
                        })
                        .finally(() => {
                            this.isProcessing = false;
                        });
                }
            },

            disable() {
                this.form.notification_email = '';
                this.submit();

                this.$toasted.success('Notification emails have been disabled');

                this.close();
            },

            close() {
                this.active = false;
                this.clearErrors();
            },

            cancel() {
                this.form.notification_email = '';
                this.close();
            }
        }
    }
</script>
