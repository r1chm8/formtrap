<template>
    <div :id="id" class="form-field" :class="fieldClass" @mouseleave="setSelectedId(null)">
        <form @submit.prevent="submit" class="field-content" v-if="isActive">
            <div class="px-4 pt-10 pb-8 md:p-8">
                <div class="-mx-2 -my-3 md:flex">
                    <div class="px-2 py-3 md:w-1/3">
                        <placeholder-input
                            v-model="field.label"
                            required
                            :has-error="hasErrorMessage('label')"
                            placeholder="Field label"
                            show-placeholder
                        ></placeholder-input>

                        <div class="error-message" v-if="hasErrorMessage('label')">
                            {{ getErrorMessage('label') }}
                        </div>
                    </div>

                    <div class="px-2 py-3 md:w-1/3">
                        <placeholder-input
                            v-model="field.name"
                            required
                            :has-error="hasErrorMessage('name')"
                            placeholder="Field name"
                            show-placeholder
                        ></placeholder-input>

                        <div class="error-message" v-if="hasErrorMessage('name')">
                            {{ getErrorMessage('name') }}
                        </div>
                    </div>

                    <div class="px-2 py-3 md:w-1/3">
                        <div class="select w-full">
                            <select v-model="field.type_id" @change="$emit('type-change')">
                                <option
                                    :key="fieldType.id"
                                    v-for="fieldType in fieldTypes"
                                    :value="fieldType.id"
                                >{{ fieldType.name }}</option>
                            </select>
                        </div>
                    </div>
                </div>

                <slot></slot>
            </div>

            <div class="border-t border-grey-300 flex items-center justify-between px-4 py-2 md:px-8">
                <div>
                    <slot name="rules">
                        <vue-checkbox
                            :id="`field_${field.id}_required`"
                            label="Required"
                            v-model="field.required"
                        ></vue-checkbox>
                    </slot>
                </div>

                <div class="flex items-center">
                    <a
                        v-if="fieldCount > 1 && field.temp"
                        class="text-grey-500 hover:text-grey-600"
                        @click="discard"
                    >Discard</a>

                    <a
                        v-if="! field.temp"
                        class="text-grey-500 hover:text-grey-600"
                        @click="cancel"
                    >Cancel</a>

                    <button
                        class="button small ml-4"
                        :class="{ 'loading': isProcessing }"
                        :disabled="isProcessing"
                    >Save changes</button>
                </div>
            </div>
        </form>
            
        <div 
            class="field-overview"
            :class="{ 'error': hasErrors }"
            v-else
            @click="setSelectedId(field.id)"
        >
            <div class="p-4 pb-6 md:p-8">
                <div class="md:w-2/3">
                    <label class="label">
                        {{ field.label || 'Unnamed field' }}

                        <span v-if="field.required" class="ml-1 text-red">*</span>
                    </label>

                    <slot name="overview"></slot>
                </div>
            </div>

            <field-actions
                :field-id="field.id"
                :version-uri="versionUri"
            ></field-actions>
        </div>
    </div>
</template>

<script>
    import { isEqual } from 'lodash';
    import { mapActions, mapGetters, mapMutations } from 'vuex';

    import PlaceholderInput from '@js/components/form/PlaceholderInput';
    import VueCheckbox from '@js/components/form/Checkbox';
    import FieldActions from '@js/views/forms/partials/fields/Actions';

    export default {
        props: {
            value: {
                type: Object,
                default: () => {}
            }
        },

        components: {
            PlaceholderInput,
            FieldActions,
            VueCheckbox
        },

        data() {
            return {
                isProcessing: false,
                field: {},
            }
        },

        computed: {
            ...mapGetters({
                errors: 'form/activeFieldErrors',
                fields: 'form/fields',
                fieldTypes: 'form/fieldTypes',
                activeId: 'form/activeFieldId',
                selectedId: 'form/selectedFieldId',
            }),

            id() {
                return `${this.field.temp ? 't' : ''}f-${this.field.id}`;
            },

            versionUri() {
                let params = this.$route.params;
                return `/api/forms/${params.id}/versions/${params.versionId}/fields`;
            },

            method() {
                return this.field.temp ? 'post' : 'patch';
            },

            fieldCount() {
                return this.fields.length;
            },

            isActive() {
                return this.activeId === this.field.id;
            },

            isSelected() {
                return this.selectedId === this.field.id;
            },

            name() {
                return this.field.name;
            },

            typeId() {
                return this.field.type_id;
            },

            fieldClass() {
                return {
                    'active': this.isActive,
                    'hover': this.isSelected
                }
            },

            hasErrors() {
                return !! Object.keys(this.errors).length;
            }
        },

        watch: {
            value: {
                handler(value) {
                    if (! isEqual(this.field, value)) {
                        this.field = { ...value };
                    }
                },
                deep: true,
                immediate: true
            },

            field: {
                handler(field) {
                    this.$emit('input', field);
                },
                deep: true
            },

            name(name) {
                this.$nextTick(() => {
                    this.field.name = name.replace(/[^\w-]/, '');
                });
            },

            typeId() {
                this.setTransition('none');
            }
        },

        created() {
            ['click', 'touchstart'].forEach(action => {
                document.addEventListener(action, this.clearSelected);
            });
        },

        destroyed() {
            ['click', 'touchstart'].forEach(action => {
                document.removeEventListener(action, this.clearSelected);
            });
        },

        inject: ['resetValues'],

        methods: {
            ...mapMutations({
                removeField: 'form/removeField',
                setActiveId: 'form/setActiveFieldId',
                setSelectedId: 'form/setSelectedFieldId',
                setTransition: 'form/setFieldTransition',
                setErrors: 'form/setActiveFieldErrors',
                clearErrors: 'form/clearActiveFieldErrors'
            }),

            ...mapActions({
                update: 'form/updateField'
            }),

            submit() {
                if (! this.isProcessing) {
                    this.isProcessing = true;

                    let uri = this.versionUri;
                    let form = { ...this.field };
                    
                    if (form.temp) {
                        delete form.id;
                        delete form.temp;
                    } else {
                        uri = `${uri}/${this.field.id}`;
                    }

                    axios[this.method](uri, form)
                        .then(response => {
                            let field = response.data.data;

                            this.update({
                                ...form,
                                id: field.id,
                                temp: false
                            });

                            this.clearErrors();
                            this.setActiveId(null);
                        })
                        .catch(error => {
                            if (error.response.status === 422) {
                                let errors = {};
                                let optionErrors = {};
                                let fieldErrors = error.response.data.errors;

                                Object.keys(fieldErrors).forEach(key => {
                                    let keySplit = key.split('.');

                                    switch(keySplit[0]) {
                                        case 'options':
                                            let option = this.field.options[keySplit[1]];

                                            optionErrors[option.id] = {
                                                ...optionErrors[option.id],
                                                [keySplit[2]]: fieldErrors[key][0]
                                            };

                                            break;
                                        default:
                                            errors[keySplit[0]] = fieldErrors[key];
                                    }
                                });

                                if (Object.keys(optionErrors).length) {
                                    errors['options'] = optionErrors;
                                }

                                this.setErrors(errors);
                            } else {
                                this.$toasted.error('An unexpected error occured.');
                            }
                        })
                        .finally(() => {
                            this.isProcessing = false;
                        });
                }
            },

            cancel() {
                this.isProcessing = false;

                this.clearErrors();
                this.setActiveId(null);
                this.resetValues();
            },

            discard() {
                this.setTransition('none');
                this.removeField(this.field.id);

                this.clearErrors();
                this.setActiveId(null);
                this.setSelectedId(null);
            },

            clearSelected() {
                if (
                    (! this.isActive && this.isSelected)
                    && (this.$el !== event.target)
                    && ! this.$el.contains(event.target)
                ) {
                    this.setSelectedId(null);
                }
            },

            hasErrorMessage(field) {
                return this.errors.hasOwnProperty(field);
            },

            getErrorMessage(field) {
                return this.errors.hasOwnProperty(field)
                    ? this.errors[field][0]
                    : null;
            }
        }
    }
</script>
