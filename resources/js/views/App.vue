<template>
    <router-view></router-view>
</template>

<script>
    import { mapActions } from 'vuex';  

    export default {
        created() {
            this.fetchUser();
        },

        methods: {
            ...mapActions({
                fetchUser: 'user/fetch'
            })
        }
    }
</script>
