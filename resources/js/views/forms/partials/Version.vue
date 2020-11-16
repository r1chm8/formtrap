<template>
    <div class="tr" :class="{ 'hide-hover': editing }">
        <div class="flex flex-grow min-w-0 w-1/2 md:w-3/5">
            <div class="cell relative w-full p-3">
                <div v-if="! editing" class="truncate" :class="{ 'font-bold': isActive }">
                    {{ version.name }}
                </div>

                <input
                    ref="input"
                    type="text"
                    @blur="updateName"
                    v-else
                    class="inline-input absolute w-full pin px-3 py-1 bg-transparent h-auto shadow-inner border-0 rounded-none focus:bg-purple-200"
                    v-model="name"
                    :readonly="isProcessing"
                    @keypress.enter="updateName"
                >
            </div>
        </div>

        <div class="flex w-1/2 md:w-2/5">
            <div class="cell w-full p-3">
                <div class="truncate" :class="{ 'font-bold': isActive }">
                    {{ version.updated_at | formatDate }}
                </div>
            </div>

            <div class="cell flex-shrink-0 w-12">
                <dropdown class="right">
                    <a slot="button" class="p-3">
                        <span class="icon">
                            <icon
                                :icon="icon"
                                :spin="isPublishing"
                            ></icon>
                        </span>
                    </a>

                    <a
                        class="dropdown-item"
                        @click="edit"
                    >Rename</a>

                    <router-link
                        :to="{
                            name: 'forms.fields',
                            params: {
                                id: form.id,
                                versionId: version.id,
                            }
                        }"
                        class="dropdown-item"
                    >Edit version</router-link>

                    <a
                        class="dropdown-item"
                        @click="createNew"
                    >Create new version</a>

                    <template v-if="isActive || isPublishing">
                        <span class="dropdown-item">
                            {{ isPublishing ? 'Publishing' : 'Publish version' }}
                        </span>

                        <div class="dropdown-divider"></div>

                        <span class="dropdown-item">
                            Delete version
                        </span>
                    </template>

                    <template v-else>
                        <a
                            class="dropdown-item"
                            @click="publish"
                        >Publish version</a>

                        <div class="dropdown-divider"></div>

                        <a
                            class="dropdown-item text-red"
                            @click="$emit('delete')"
                        >Delete version</a>
                    </template>
                </dropdown>
            </div>                
        </div>
    </div>
</template>

<script>
    import { mapGetters, mapMutations } from 'vuex';
    import moment from 'moment';
    import Dropdown from '@js/components/ui/Dropdown';

    export default {
        components: { Dropdown },

        filters: {
            formatDate(date) {
                return moment.utc(date).local().fromNow();
            }
        },

        props: {
            item: {
                type: Object,
                default: () => {}
            }
        },

        data() {
            return {
                version: {},

                name: '',
                editing: false,
                isProcessing: false,
                isPublishing: false
            }
        },

        computed: {
            ...mapGetters({
                form: 'form/data'
            }),

            isActive() {
                return this.form.version.id === this.version.id;
            },

            uri() {
                return `/api/forms/${this.form.id}/versions/${this.version.id}`;
            },

            icon() {
                return this.isPublishing ? 'spinner' : 'cogs';
            }
        },

        watch: {
            item: {
                handler(item) {
                    this.version = { ...item };
                },
                deep: true,
                immediate: true
            }
        },

        methods: {
            ...mapMutations({
                updateForm: 'form/updateData'
            }),

            publish() {
                this.isPublishing = true;

                axios.patch(`${this.uri}`, {
                    published: true
                }).then(response => {
                    this.version = response.data.data;

                    this.updateForm({
                        version: this.version
                    });

                    this.isPublishing = false;
                });
            },

            createNew() {
                axios.post(`/api/forms/${this.$route.params.id}/versions`, {
                        duplicate_of: this.version.id
                    })
                    .then(response => {
                        let version = response.data.data;

                        this.$router.push({
                            name: 'forms.fields',
                            params: {
                                id: this.$route.params.id,
                                versionId: version.id
                            }
                        });
                    });
            },

            edit() {
                this.editing = true;
                this.name = this.version.name;

                Vue.nextTick(() => {
                    this.$refs.input.focus();
                });
            },

            updateName() {
                this.editing = false;

                if (this.isProcessing) {
                    return;
                }

                if (this.name.length && this.name !== this.version.name) {
                    this.isProcessing = true;

                    axios.patch(this.uri, { name: this.name }).then(response => {
                        this.version.name = this.name;
                    }).catch(error => {
                        if (error.response.status === 422) {
                            let errors = error.response.data.errors;

                            this.$toasted.error(errors[0]);
                        } else {
                            this.$toasted.error('An unexpected error occured.');
                        }
                    }).finally(() => {
                        this.isProcessing = false;
                    });
                }

                this.$refs.input.blur();
            }
        }
    }
</script>
