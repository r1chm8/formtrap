<template>
    <dashboard>
        <div class="flex flex-col flex-grow">
            <sub-header>
                <h1 class="title">Manage your account</h1>

                <a class="hamburger -ml-3 mr-2 lg:hidden" @click="openRightPanel">
                    <span class="hamburger-holder">
                        <span class="hamburger-inner"></span>
                    </span>
                </a>
            </sub-header>

            <div class="container mx-auto px-4 py-6 md:px-8">
                <div class="lg:flex lg:-mx-3">
                    <div class="hidden w-64 flex-shrink-0 px-4 lg:block">
                        <div class="bg-white shadow">
                            <settings-nav></settings-nav>
                        </div>
                    </div>

                    <div class="lg:flex-grow lg:px-3">
                        <router-view></router-view>
                    </div>
                </div>
            </div>    
        </div>

        <template slot="right-panel">
            <settings-nav></settings-nav>
        </template>
    </dashboard>
</template>

<script>
    import { mapActions } from 'vuex';

    import Dashboard from '@js/views/layouts/Dashboard';
    import SettingsNav from './partials/Nav'

    export default {
        components: {
            SettingsNav,
            Dashboard
        },

        methods: {
            ...mapActions({
                openRightPanel: 'panel/openRight'
            })
        }
    }
</script>
