<template>
    <div class="bg-white shadow px-4 py-6 md:px-8 md:pt-6 md:pb-8">
        <form @submit.prevent="submit" class="lg:w-2/3">
            <h1 class="subtitle mb-6">Change your password</h1>

            <form-field input="old_password" label="Current password" required>
                <vue-input
                    id="old_password"
                    type="password"
                    v-model="form.old_password"
                    required
                    autocomplete="new-password"
                ></vue-input>
            </form-field>

            <form-field input="password" label="New password" required>
                <vue-input
                    id="password"
                    type="password"
                    v-model="form.password"
                    required
                    autocomplete="new-password"
                ></vue-input>
            </form-field>
            
            <form-field input="password_confirmation" label="Confirm password" required>
                <vue-input
                    id="password_confirmation"
                    type="password"
                    v-model="form.password_confirmation"
                    required
                    autocomplete="new-password"
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
    function initalValues() {
        return {
            old_password: '',
            password: '',
            password_confirmation: ''
        }
    };

    import { mapGetters } from 'vuex';
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
                form: initalValues()
            }
        },

        computed: {
            ...mapGetters({
                user: 'user/data'
            })
        },

        methods: {
            onSuccess() {
                this.form = initalValues();

                this.$toasted.success('Password successfully updated.');
            }
        }
    }
</script>
