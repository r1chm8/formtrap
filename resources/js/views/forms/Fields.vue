<template>
    <div class="flex flex-col flex-grow">
        <transition name="fade">
            <div v-if="activeFieldId" class="field-overlay"></div>
        </transition>

        <sub-header>
            <h1 class="title flex items-center">
                <span class="flex-shrink-0">
                    Managing
                </span>

                <editable-form-name></editable-form-name>
            </h1>
        </sub-header>

        <div class="flex flex-col flex-grow pt-8 md:flex md:flex-col-reverse">
            <div class="relative container mx-auto flex-grow px-4 md:px-8 md:pb-8 lg:max-w-3xl">
                <div v-if="fields.length" class="bg-white shadow mb-8 md:mr-16">
                    <transition-group :name="fieldTransition">
                        <field-holder
                            :key="field.id"
                            v-for="field in fields"
                            :item="field"
                        ></field-holder>
                    </transition-group>
                </div>

                <div class="mb-8">
                    <router-link
                        :to="{
                            name: 'forms.manage',
                            params: {
                                id: $route.params.id
                            }
                        }"
                        class="button"
                    >Finish Editing</router-link>
                </div>

                <loader></loader>
            </div>

            <div class="form-actions-holder sticky">
                <div class="container mx-auto px-4 md:relative md:px-8 lg:max-w-3xl">
                    <ul class="form-actions">
                        <li>
                            <a @click="addNewField" title="Add new field">
                                <span class="icon">
                                    <icon icon="plus-circle"></icon>
                                </span>
                            </a>
                        </li>

                        <li>
                            <a
                                :href="formData.endpoint"
                                title="Preview"
                                target="_blank"
                            >
                                <span class="icon">
                                    <icon icon="eye"></icon>
                                </span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import { mapActions, mapGetters, mapMutations } from 'vuex';
    import scrollTo from 'vue-scrollto';

    import FieldHolder from './partials/fields/Holder';
    import EditableFormName from './partials/EditName';

    const defaultField = () => {
        return {
            temp: true,
            type_id: 1,
            name: '',
            label: '',
            required: false
        };
    };

    export default {
        components: {
            EditableFormName,
            FieldHolder
        },
        
        computed: {
            ...mapGetters({
                formData: 'form/data',
                fields: 'form/fields',
                activeFieldId: 'form/activeFieldId',
                fieldTransition: 'form/fieldTransition',
                generateFieldId: 'form/uniqueFieldId'
            }),

            form() {
                return {
                    fields: this.fields
                };
            },

            fieldCount() {
                return this.fields.length;
            }
        },

        watch: {
            fieldCount: {
                handler(count) {
                    if (! count) {
                        this.addField({
                            ...defaultField(),
                            id: this.generateFieldId,
                        });
                    }
                }
            }
        },

        created() {
            this.fetchFields({
                formId: this.$route.params.id,
                versionId: this.$route.params.versionId
            }).then(() => {
                if (! this.fieldCount) {
                    this.addField({
                        ...defaultField(),
                        id: this.generateFieldId,
                    });
                }
            });

            this.fetchFieldTypes();
        },

        beforeDestroy() {
            this.resetForm();
        },

        methods: {
            ...mapActions({
                addField: 'form/addField',
                fetchFields: 'form/fetchFields',
                fetchFieldTypes: 'form/fetchFieldTypes',
                resetForm: 'form/reset'
            }),

            addNewField() {
                this.addField({
                    ...defaultField(),
                    id: this.generateFieldId,
                }).then(fieldId => {
                    this.$nextTick(() => {
                        scrollTo.scrollTo(`#tf-${fieldId}`, 700, {
                            offset: -56
                        });
                    });
                });
            }
        }
    }
</script>
