<template>
    <ul class="nav">
        <router-link
            :to="{ name: 'forms.manage' }"
            tag="li"
            :class="{ 'active': routeName === 'forms.manage' }"
        ><a>Overview</a></router-link>

        <!-- <router-link
            :to="{ name: 'forms.manage.integrations' }"
            tag="li"
            :class="{ 'active': routeName === 'forms.manage.integrations' }"
        ><a>Integrations</a></router-link> -->

        <router-link
            :to="{ name: 'forms.manage.versions' }"
            tag="li"
            :class="{ 'active': routeName === 'forms.manage.versions' }"
        ><a>Versions</a></router-link>

        <router-link
            :to="{ name: 'forms.manage.settings' }"
            tag="li"
            :class="{ 'active': routeName === 'forms.manage.settings' }"
        ><a>Settings</a></router-link>
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
