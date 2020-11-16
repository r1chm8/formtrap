<template>
    <dashboard>
        <div class="flex flex-col flex-grow min-w-0">
            <sub-header v-if="form">
                <h1 class="title flex items-center">
                    <span class="flex-shrink-0">
                        Managing
                    </span>

                    <editable-name />
                </h1>

                <div class="flex items-center ml-4">
                    <a class="hamburger -mr-2 lg:hidden" @click="openRightPanel">
                        <span class="hamburger-holder">
                            <span class="hamburger-inner"></span>
                        </span>
                    </a>
                </div>
            </sub-header>

            <div class="flex flex-col flex-grow">
                <div class="container mx-auto px-4 py-6 md:px-8">
                    <div class="lg:flex lg:-mx-3">
                        <div class="hidden w-64 flex-shrink-0 px-4 lg:block">
                            <div class="bg-white shadow">
                                <manage-nav></manage-nav>
                            </div>
                        </div>

                        <div class="min-w-0 lg:flex-grow lg:px-3">
                            <router-view></router-view>
                        </div>
                    </div>
                </div>    
            </div>
        </div>
        
        <template slot="right-panel">
            <manage-nav></manage-nav>
        </template>
    </dashboard>
</template>

<script>
    import { mapActions, mapGetters } from 'vuex';

    import Dashboard from '@js/views/layouts/Dashboard';
    import EditableName from '../partials/EditName';
    import ManageNav from '../partials/ManageNav'

    export default {
        components: {
            Dashboard,
            EditableName,
            ManageNav
        },

        computed: {
            ...mapGetters({
                form: 'form/data'
            })
        },

        created() {
            this.fetchForm(this.$route.params.id);
        },

        methods: {
            ...mapActions({
                fetchForm: 'form/fetchData',
                openRightPanel: 'panel/openRight'
            })
        }
    }
</script>
