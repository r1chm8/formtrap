<template>
    <modal :active="active" @close="close" @escape="close">
        <div class="modal-content max-w-2xl">
            <div class="bg-white rounded shadow mx-4">
                <div class="flex items-center justify-between bg-purple-200 p-4 rounded-t md:px-8">
                    <h2 class="title">
                        Create new form
                    </h2>

                    <a class="icon text-grey-600 -mr-2" @click="cancel">
                        <icon icon="times"></icon>
                    </a>
                </div>

                <form @submit.prevent="submit" class="px-4 pt-6 pb-8 md:px-8">
                    <form-field input="name" label="Form name" required>
                        <vue-input
                            id="name"
                            ref="input"
                            v-model="form.name"
                            required
                            autofocus
                            @keypress.enter="submit"
                        ></vue-input>
                    </form-field>

                    <form-field>
                        <button
                            class="button primary"
                            :class="{ 'loading': isProcessing }"
                            :disabled="isProcessing"
                        >Create form</button>
                    </form-field>
                </form>
            </div>
        </div>
    </modal>
</template>

<script>
    import FormField from '@js/components/form/Field';
    import Modal from '@js/components/ui/Modal';
    import VueInput from '@js/components/form/Input';

    export default {
        components: {
            FormField,
            Modal,
            VueInput
        },

        props: {
            active: {
                type: Boolean,
                default: false
            }
        },

        data() {
            return {
                form: {
                    name: ''
                },
                
                isProcessing: false
            };
        },

        watch: {
            active(active) {
                if (active) {
                    this.$nextTick(() => {
                        this.$refs.input.$el.focus();
                    });
                }
            }
        },

        methods: {
            submit() {
                if (! this.isProcessing) {
                    this.isProcessing = true;

                    axios.post('/api/forms', this.form)
                        .then(response => {
                            let form = response.data.data;

                            this.$router.push({
                                name: 'forms.fields',
                                params: {
                                    id: form.id,
                                    versionId: form.version.id
                                }
                            });
                        })
                        .catch(error => {
                            if (error.response.status === 422) {
                                let errors = error.response.data.errors;
                                this.$toasted.error(errors['name'][0], { duration: 3000 });
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

            close() {
                this.$emit('close');
            },

            cancel() {
                this.form.name = '';
                this.close();
            }
        }
    }
</script>
