<template>
    <div class="bg-white shadow px-4 py-6 md:px-8 md:pt-6 md:pb-8">
        <form @submit.prevent="submit" class="lg:w-2/3">
            <h1 class="subtitle mb-1">Change email</h1>
            <p class="mb-8">Your current email address is <strong>{{ user.email }}</strong></p>

            <form-field input="email" label="New email address" required>
                <vue-input
                    id="email"
                    type="email"
                    v-model="form.email"
                    required
                ></vue-input>
            </form-field>

            <form-field>
                <button
                    class="button primary"
                    :class="{ 'loading': isProcessing }"
                >Save changes</button>
            </form-field>
        </form>
    </div>
</template>

<script>
    import { mapGetters, mapMutations } from 'vuex';
    import formMixin from '@js/mixins/form';

    export default {
        mixins: [ formMixin ],

        props: {
            action: {
                default: '/api/user'
            },

            method: {
                default: 'patch'
            }
        },

        data() {
            return {
                form: {
                    email: ''
                }
            }
        },

        computed: {
            ...mapGetters({
                user: 'user/data'
            })
        },

        methods: {
            ...mapMutations({
                updateUser: 'user/update'
            }),

            onSuccess(response) {
                this.updateUser(response.data.data);
                this.$toasted.success('Email successfully updated.');

                this.form.email = '';
            }
        }
    }
</script>
