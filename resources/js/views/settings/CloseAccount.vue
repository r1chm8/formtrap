<template>
    <div class="bg-white shadow px-4 py-6 md:px-8 md:pt-6 md:pb-8">
        <form @submit.prevent="submit" class="lg:w-2/3">
            <h1 class="subtitle mb-1">Delete your account</h1>
            
            <p class="mb-8">
                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Fusce lectus massa,
                elementum a interdum vitae, egestas a turpis. Duis auctor justo arcu, sit amet.
            </p>

            <div class="field">
                <label for="password" class="label">Confirm password</label>

                <div class="control">
                    <input
                        id="password"
                        type="password"
                        class="input"
                        v-model="form.password"
                        required
                        autocomplete="new-password"
                    >
                </div>
            </div>

            <div class="field">
                <div class="control">
                    <button
                        class="button primary"
                        :class="{ 'loading': isProcessing }"
                    >Delete account</button>
                </div>
            </div>
        </form>
    </div>
</template>

<script>
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
                form: {
                    email: ''
                }
            }
        },

        computed: {
            ...mapGetters({
                user: 'user/data'
            })
        }
    }
</script>
