<template>
    <input
        ref="input"
        type="text"
        @blur="submit"
        class="inline-input text-lg w-1/2 sm:w-full focus:border-grey-400"
        v-model="form.name"
        :readonly="isProcessing"
        @keypress.enter="submit"
    >
</template>

<script>
    import { mapActions, mapGetters, mapMutations } from 'vuex';

    export default {
        computed: {
            ...mapGetters({
                item: 'form/data'
            })
        },

        data() {
            return {
                editing: false,
                isProcessing: false,

                form: {
                    name: ''
                }
            }
        },

        watch: {
            item: {
                handler(item) {
                    this.form = {
                        name: item.name
                    }
                },
                immediate: true
            }
        },

        created() {
            this.fetchForm(this.$route.params.id);
        },

        methods: {
            ...mapActions({
                fetchForm: 'form/fetchData'
            }),

            ...mapMutations({
                updateForm: 'form/updateData'
            }),

            submit() {
                if (this.isProcessing) {
                    return;
                }

                if (this.form.name.length && this.form.name !== this.item.name) {
                    this.isProcessing = true;

                    axios.patch(`/api/forms/${this.item.id}`, this.form)
                        .then(response => {
                            this.updateForm({
                                name: response.data.data.name
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
                } else {
                    this.form.name = this.item.name;
                }

                this.$refs.input.blur();
            }
        }
    }
</script>

<style lang="scss" scoped>
    .inline-input {
        padding: 0.5rem 0.75rem 0.5rem 0.5rem;

        &:focus {
            margin-left: 0.5rem;
        }
    }
</style>

