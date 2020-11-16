<template>
    <component
        :is="getComponent(field.type_id)"
        v-model="field"
    ></component>
</template>

<script>
    import CheckboxField from './Checkbox';
    import RadioField from './Radio';
    import SelectField from './Select';
    import TextField from './Text';
    import TextareaField from './Textarea';

    export default {
        components: {
            CheckboxField,
            RadioField,
            SelectField,
            TextField,
            TextareaField
        },

        props: {
            item: {
                type: Object,
                default: () => {}
            }
        },

        data() {
            return {
                field: {},
            }
        },

        computed: {
            fieldMap() {
                return {
                    1: 'Text',
                    2: 'Textarea',
                    3: 'Radio',
                    4: 'Checkbox',
                    5: 'Select'
                };
            }
        },

        watch: {
            item: {
                handler(item) {
                    this.field = { ...item };
                },
                immediate: true
            }
        },

        methods: {
            getComponent(fieldId) {
                return this.fieldMap[fieldId] + 'Field';
            }
        },

        provide() {
            return {
                resetValues: () => {
                    this.field = { ...this.item };
                }
            }
        }
    }
</script>
