<template>
    <ul class="nav">
        <router-link
            :to="{ name: 'settings.general' }"
            tag="li"
            :class="{ 'active': routeName === 'settings.general' }"
        ><a>General</a></router-link>

        <router-link
            :to="{ name: 'settings.change-email' }"
            tag="li"
            :class="{ 'active': routeName === 'settings.change-email' }"
        ><a>Email</a></router-link>

        <router-link
            :to="{ name: 'settings.change-password' }"
            tag="li"
            :class="{ 'active': routeName === 'settings.change-password' }"
        ><a>Password</a></router-link>

        <router-link
            :to="{ name: 'settings.subscription' }"
            tag="li"
            :class="{ 'active': routeName === 'settings.subscription' }"
        ><a>Subscription</a></router-link>
    </ul>
</template>

<script>
    export default {
        props: {
            navClass: {
                type: String,
                default: null
            }
        },

        computed: {
            routeName() {
                return this.$route.name;
            }
        },
    }
</script>
