<template>
    <div class="min-h-full flex flex-col" v-if="! isLoading('app.*')">
        <app-header></app-header>

        <div class="flex flex-grow flex-col bg-grey-200">
            <slot>
                <router-view></router-view>
            </slot>
        </div>

        <app-footer></app-footer>

        <right-panel class="lg:hidden">
            <slot name="right-panel"></slot>
        </right-panel>
    </div>
</template>

<script>
    import { mapGetters } from 'vuex';

    import AppHeader from '@js/views/partials/Header';
    import AppFooter from '@js/views/partials/Footer';
    import RightPanel from '@js/views/partials/RightPanel';

    export default {
        components: {
            AppHeader,
            AppFooter,
            RightPanel
        },

        computed: {
            ...mapGetters({
                isLoading: 'loader/isLoading'
            })
        }
    }
</script>
