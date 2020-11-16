<template>
    <header class="flex relative z-50 items-center h-18 bg-blue-500-to-right-purple-500">
        <div class="container flex items-center justify-between mx-auto px-4 md:px-8">
            <router-link :to="{ name: 'forms.index' }" class="hidden md:block md:mr-10">
                <img
                    src="/images/logo.svg"
                    alt="Formtrap"
                    class="block w-32"
                >
            </router-link>

            <div class="mr-10 flex-grow">
                <navigation></navigation>
            </div>

            <dropdown class="right">
                <div slot="button" class="flex items-center" v-if="user">
                    <a class="flex items-center flex-grow text-white select-none hover:text-grey-300">
                        <img
                            src="/images/emblem.svg"
                            alt="Formtrap"
                            class="mr-1 md:hidden"
                        >

                        <span class="hidden md:inline">
                            {{ user.name }}
                        </span>

                        <span class="icon">
                            <icon icon="caret-down" size="sm"></icon>
                        </span>
                    </a>

                    <figure class="hidden avatar bg-purple-200 w-10 h-10 ml-2 md:flex">
                        <img :src="`${ user.gravatar_url }?d=identicon`" :alt="user.name">
                    </figure>
                </div>

                <router-link
                    :to="{ name: 'settings.general' }"
                    class="dropdown-item"
                >Settings</router-link>

                <a
                    class="dropdown-item"
                    @click="logout"
                >Logout</a>
            </dropdown>
        </div>
    </header>
</template>

<script>
    import { mapActions, mapGetters } from 'vuex';
    
    import Dropdown from '@js/components/ui/Dropdown';
    import Navigation from './Navigation';

    export default {
        components: {
            Dropdown,
            Navigation
        },

        computed: {
            ...mapGetters({
                user: 'user/data'
            })
        },

        methods: {
            ...mapActions({
                logout: 'user/logout'
            })
        }
    }
</script>
