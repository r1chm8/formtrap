<template>
    <div class="relative bg-white shadow px-4 py-6 md:p-8">
        <h1 class="subtitle mb-4">Versions</h1>

        <div class="table-style" v-if="versions.length">
            <div class="tr th">
                <div class="flex flex-grow w-1/2 md:w-3/5">
                    <div class="cell w-full p-3">
                        Name
                    </div>
                </div>

                <div class="flex w-1/2 md:w-2/5">
                    <div class="cell w-full p-3">
                        Last Updated
                    </div>
                </div>
            </div>

            <version
                :key="version.id"
                v-for="version in versions"
                :item="version"
                @delete="openConfirmation(version)"
            ></version>
        </div>

        <loader class="bg-grey-200 -mx-1"></loader>

        <confirmation
            v-slot="{ item: version }"
            @confirm="remove"
            button-text="Delete"
            button-class="red-500"
        >
            Are you sure you want to delete the version <strong>{{ version.name }}</strong>?<br>
            Enquiries associated to this version will also be deleted.<br>
            <strong>This action can not be undone.</strong>
        </confirmation>
    </div>
</template>

<script>
    import { mapActions } from 'vuex';
    import Confirmation from '@js/components/ui/confirmation';
    import Version from './partials/Version';

    export default {
        components: {
            Confirmation,
            Version
        },

        data() {
            return {
                versions: []
            }
        },

        created() {
            this.fetchVersions();
        },

        methods: {
            ...mapActions({
                openConfirmation: 'confirmation/open'
            }),

            fetchVersions() {
                this.startLoading('primary.form-versions');

                axios.get(`/api/forms/${this.$route.params.id}/versions`).then(response => {
                    this.versions = response.data.data;
                    
                    this.stopLoading('primary.form-versions');
                });
            },

            remove(version) {
                axios.delete(`/api/forms/${this.$route.params.id}/versions/${version.id}`);
                this.versions = this.versions.filter(({ id }) => {
                    return id !== version.id;
                });
            }
        }
    }
</script>
