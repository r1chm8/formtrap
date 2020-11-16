<template>
    <div class="bg-white shadow px-4 py-6 md:px-8 md:pt-6 md:pb-8">
        <h1 class="subtitle mb-4">Your profile</h1>

        <hr class="mb-6">

        <div class="-mx-8 md:flex">
            <form @submit.prevent="submit" class="px-8 mb-6 md:flex-grow">
                <form-field input="name" label="Your name" required>
                    <vue-input
                        id="name"
                        v-model="form.name"
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

            <div class="px-8 md:flex-shrink-0">
                <h2 class="subtitle text-sm mb-2">Avatar</h2>

                <figure class="avatar bg-purple-200 w-48 h-48 mb-1">
                    <img :src="`${ user.gravatar_url }?s=400&d=identicon`" :alt="user.name">
                </figure>

                <div class="text-xs text-grey-500">
                    Avatars are managed via
                    <a href="https://gravatar.com/" target="_blank">gravatar</a>.
                </div>
            </div>
        </div>
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
                    name: ''
                }
            }
        },

        computed: {
            ...mapGetters({
                user: 'user/data'
            })
        },

        created() {
            this.form.name = this.user.name;
        },

        methods: {
            ...mapMutations({
                updateUser: 'user/update'
            }),

            onSuccess() {
                this.$toasted.success('Profile successfully updated.');

                this.updateUser({
                    name: this.form.name
                });
            }
        }
    }
</script>
