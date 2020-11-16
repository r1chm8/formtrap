<template>
    <field-wrap v-model="field" @type-change="reset">
        <field-options v-model="field.options"></field-options>

        <div slot="overview" class="control pointer-events-none">
            <div class="-mx-2 md:flex md:flex-wrap">
                <div :key="index" class="px-2 py-1 md:w-1/3" v-for="(option, index) in field.options">
                    <input type="checkbox" class="checkbox">
                    <label>{{ option.label || 'label' }}</label>
                </div>
            </div>
        </div>
    </field-wrap>
</template>

<script>
    import fieldMixin from '@js/mixins/field';
    import FieldOptions from './Options';

    export default {
        mixins: [ fieldMixin ],
        components: { FieldOptions },

        data() {
            return {
                field: {
                    id: null,
                    temp: false,
                    type_id: null,
                    name: '',
                    label: '',
                    required: false,
                    options: []
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
                        }]
                };
            },

            reset() {
                delete this.field.options;
            }
        }
    }
</script>
