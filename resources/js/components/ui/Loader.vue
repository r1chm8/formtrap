<template>
    <transition name="loader">
        <div v-if="isLoading('primary.*')" class="loader">
            <transition name="loader">
                <div class="icon large" v-if="showSpinner">
                    <icon icon="spinner" size="lg" spin pulse></icon>
                </div>
            </transition>
        </div>
    </transition>
</template>

<script>
    import { mapGetters } from 'vuex';

    export default {
        data() {
            return {
                showSpinner: false
            }
        },

        computed: {
            ...mapGetters({
                isLoading: 'loader/isLoading'
            })
        },

        created() {
            setTimeout(() => {
                this.showSpinner = true;
            }, 250);
        }
    }
</script>
