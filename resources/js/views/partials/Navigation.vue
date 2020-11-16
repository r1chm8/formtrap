<template>
    <ul class="top-navigation list-reset">
        <li>
            <router-link
                :to="{ name: 'forms.index' }"
                :class="{ 'active': section === 'forms' }"
            >
                <span class="icon md:hidden">
                    <icon icon="list-alt" size="lg"></icon>
                </span>

                <span class="hidden md:block">
                    Forms
                </span>
            </router-link>
        </li>

        <li>
            <router-link
                :to="{ name: 'enquiries.index' }"
                :class="{ 'active': section === 'enquiries' }"
            >
                <span class="icon md:hidden">
                    <icon icon="inbox" size="lg"></icon>
                </span>

                <span class="hidden md:block">
                    Enquiries
                </span>
            </router-link>
        </li>

        <li class="hidden md:block">
            <router-link
                :to="{ name: 'settings.general' }"
                :class="{ 'active': section === 'settings' }"
            >Settings</router-link>
        </li>
    </ul>
</template>

<script>
    export default {
        computed: {
            section() {
                let meta = this.$route.matched[this.$route.matched.length - 1].meta;

                return meta.hasOwnProperty('section')
                    ? meta.section
                    : this.$route.matched[0].meta.section;
            }
        }
    }
</script>
