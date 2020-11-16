<template>
    <div>
        <sub-header>
            <h1 class="title">Forms</h1>

            <a
                class="button outlined"
                @click="showCreateModal = true"
            >New Form</a>
        </sub-header>

        <div class="relative">
            <div v-if="! forms.length" class="flex flex-col flex-grow justify-center">
                <div class="max-w-2xl text-center mx-auto px-8 py-16">
                    <img src="/images/icons/form.svg" alt="" class="inline mb-8">

                    <h1 class="text-lg text-purple uppercase mb-2">Create your first form</h1>

                    <div class="mb-6">
                        <p>
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla eros libero, lobortis
                            bibendum, aliquet molestie dui. Curabitur bibendum erat id enim volutpat condimentum.
                        </p>
                    </div>

                    <a
                        class="button primary"
                        @click="showCreateModal = true"
                    >Create Form</a>
                </div>
            </div>

            <div v-if="forms.length" class="container mx-auto px-4 py-8 md:px-8">
                <div class="bg-white shadow">
                    <div class="table-style">
                        <router-link
                            tag="div"
                            :to="{
                                name: 'forms.manage',
                                params: {
                                    id: form.id
                                }
                            }"
                            :key="form.id"
                            v-for="form in forms"
                            class="tr cursor-pointer"
                        >
                            <div class="cell flex-grow p-4 md:py-5">
                                <div class="truncate">
                                    <div class="text-base">
                                        {{ form.name }}
                                    </div>

                                    <div class="text-grey-500 text-xs">
                                        Created: {{ form.created_at | formatDate }}
                                    </div>
                                </div>
                            </div>

                            <div class="cell hidden flex-shrink-0 p-4 sm:flex md:py-5">
                                <ul class="action-bar">
                                    <li>
                                        <router-link
                                            :to="{
                                                name: 'enquiries.index',
                                                query: {
                                                    form: form.id
                                                }
                                            }"
                                            title="Enquiries"
                                            class="action-bar-item"
                                        >
                                            <span class="icon">
                                                <icon icon="inbox"></icon>
                                            </span>
                                        </router-link>
                                    </li>

                                    <li>
                                        <router-link
                                            :to="{
                                                name: 'forms.fields',
                                                params: {
                                                    id: form.id,
                                                    versionId: form.version.id
                                                }
                                            }"
                                            title="Edit form fields"
                                            class="action-bar-item"
                                        >
                                            <span class="icon">
                                                <icon icon="pencil-alt"></icon>
                                            </span>
                                        </router-link>
                                    </li>

                                    <li>
                                        <router-link
                                            :to="{
                                                name: 'forms.manage',
                                                params: {
                                                    id: form.id
                                                }
                                            }"
                                            title="Manage form"
                                            class="action-bar-item"
                                        >
                                            <span class="icon">
                                                <icon icon="cog"></icon>
                                            </span>
                                        </router-link>
                                    </li>
                                </ul>
                            </div>
                        </router-link>
                    </div>
                </div>
            </div>

            <loader class="bg-grey-200"></loader>
        </div>

        <create-modal
            :active="showCreateModal"
            @close="showCreateModal = false"
        ></create-modal>
    </div>
</template>

<script>
    import moment from 'moment';
    import CreateModal from './partials/CreateModal';
    
    export default {
        components: { CreateModal },

        filters: {
            formatDate(date) {
                return moment.utc(date).local().format('Do MMM YYYY');
            }
        },

        data() {
            return {
                forms: [],
                showCreateModal: false
            }
        },

        created() {
            this.fetchForms();
        },

        methods: {
            fetchForms() {
                this.startLoading('primary.forms');

                axios.get('/api/forms').then(response => {
                    this.forms = response.data.data;

                    this.stopLoading('primary.forms');
                });
            }
        }
    }
</script>
