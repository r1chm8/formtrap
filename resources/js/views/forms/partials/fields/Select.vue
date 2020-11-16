<template>
    <field-wrap v-model="field" @type-change="reset">
        <field-options v-model="field.options"></field-options>

        <div slot="rules" class="-mx-3 -mb-2 sm:flex">
            <div class="px-3 pb-2">
                <vue-checkbox
                    :id="`field_${field.id}_required`"
                    label="Required"
                    v-model="field.required"
                ></vue-checkbox>
            </div>

            <div class="px-3 pb-2">
                <vue-checkbox
                    :id="`field_${field.id}_multiple`"
                    label="Multiple"
                    v-model="field.multiple"
                ></vue-checkbox>
            </div>
        </div>

        <div slot="overview" class="control pointer-events-none">
            <div class="select w-full" :class="{ 'multiple': field.multiple }">
                <select :class="{ 'small': field.multiple }" :multiple="field.multiple" readonly>
                    <option selected>Please select...</option>
                    <option :key="option.id" v-for="option in field.options">
                        {{ option.label }}
                    </option>
                </select>
            </div>
        </div>
    </field-wrap>
</template>

<script>
    import fieldMixin from '@js/mixins/field';

    import FieldOptions from './Options';
    import VueCheckbox from '@js/components/form/Checkbox';

    export default {
        mixins: [ fieldMixin ],

        components: {
            FieldOptions,
            VueCheckbox
        },

        data() {
            return {
                field: {
                    id: null,
                    temp: false,
                    type_id: null,
                    name: '',
                    label: '',
                    required: false,
                    options: [],
                    multiple: false
                }
            }
        },

        methods: {
            setValues(values) {
                this.field = {
                    id: values.id,
                    temp: values.hasOwnProperty('temp') ? values.temp: false,
                    type_id: values.type_id,
                    name: values.name,
                    label: values.label,
                    required: values.required,

                    options: values.hasOwnProperty('options')
                        ? [ ...values.options ]
                        : [{
                            id: 1,
                            value: '',
                            label: ''
                        }],

                    multiple: values.hasOwnProperty('multiple')
                        ? values.multiple
                        : false
                };
            },

            reset() {
                delete this.field.options;
                delete this.field.multiple;
            }
        }
    }
</script>
