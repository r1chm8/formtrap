<template>
    <div>
        <div class="bg-white shadow px-4 py-6 mb-8 md:p-8">
            <form @submit.prevent="updateSettings">
                <h3 class="subtitle mb-4">Form settings</h3>

                <div class="mb-2 md:max-w-xs">
                    <form-field input="redirect_url" label="Redirect url">
                        <vue-input
                            id="redirect_url"
                            v-model="settings.redirect_url"
                            :placeholder="`eg: ${ appUrl }/thank-you`"
                        ></vue-input>
                    </form-field>
                </div>

                <div class="mb-6">
                    Redirect users back to your website after successfully completing this form when hosted on formtrap.io.
                </div>

                <form-field>
                    <button
                        class="button primary"
                        :class="{ 'loading': isProcessing }"
                    >Save changes</button>
                </form-field>
            </form>

            <!-- TODO save form button and make API request -->

            <!-- <form-field>
                <checkbox
                    id="email_notifications"
                    label="Email notification"
                    v-model="form.email_notifications"
                ></checkbox>
            </form-field>

            <form-field>
                <checkbox
                    id="summary_notification"
                    label="Weekly summary email"
                    v-model="form.summary_notification"
                ></checkbox>
            </form-field> -->
        </div>

        <div class="bg-white shadow px-4 py-6 md:p-8">
            <h3 class="subtitle text-red mb-4">Delete form</h3>

            <div class="mb-6">
                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur semper dolor metus, 
                nec pulvinar justo aliquam sit amet. Curabitur euismod lobortis posuere.
            </div>

            <form @submit.prevent="removeForm">
                <div class="field addons md:max-w-md">
                    <div class="control flex-grow">
                        <vue-input
                            id="remove_form_name"
                            v-model="remove.name"
                            required
                            :disabled="removing"
                        ></vue-input>
                    </div>

                    <div class="control">
                        <button
                            class="button red"
                            :class="{ 'loading': removing }"
                            :disabled="removing"
                        >Delete</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
    import { mapActions, mapGetters } from 'vuex';
    import Checkbox from '@js/components/form/Checkbox';
    import FormField from '@js/components/form/Field';
    import VueInput from '@js/components/form/Input';

    export default {
        components: {
            Checkbox,
            FormField,
            VueInput
        },

        data() {
            return {
                settings: {
                    name: '',
                    redirect_url: ''
                },
                isProcessing: false,

                remove: {
                    name: ''
                },
                removing: false
            }
        },

        computed: {
            ...mapGetters({
                form: 'form/data'
            }),

            appUrl() {
                return process.env.MIX_APP_URL;
            }
        },

        watch: {
            form: {
                handler(form) {
                    this.settings.name = form.name;
                    this.settings.redirect_url = form.redirect_url;
                },
                deep: true,
                immediate: true
            }
        },

        methods: {
            ...mapActions({
                setErrors: 'errors/set',
                clearErrors: 'errors/clear'
            }),

            updateSettings() {
                this.isProcessing = true;

                axios.patch(`/api/forms/${this.form.id}`, this.settings)
                    .then(response => {
                        this.clearErrors();

                        this.$toasted.success('Form settings successfully updated.', {
                            duration: 1500
                        });
                    })
                    .catch(error => {
                        if (error.response.status === 422) {
                            this.setErrors(error.response.data.errors);
                        } else {
                            this.$toasted.error('An unexpected error occured.');
                        }
                    })
                    .finally(() => {
                        this.isProcessing = false;
                    });
            },

            removeForm() {
                if (this.remove.name !== this.form.name) {
                    this.remove.name = '';

                    this.$toasted.error('That name does not match the form name.', {
                        duration: 3000
                    });
                } else {
                    this.removing = true;

                    axios.delete(`/api/forms/${this.form.id}`).then(() => {
                        this.$router.push({
                            name: 'forms.index'
                        });
                    }).catch(error => {
                        this.$toasted.error('An unexpected error occured.', {
                            duration: 3000
                        });
                    }).finally(() => {
                        this.removing = false;
                    })
                }
            }
        }
    }
</script>
