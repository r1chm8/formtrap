<template>
    <field-wrap v-model="field">
        <div slot="overview" class="control pointer-events-none">
            <textarea
                type="text"
                class="textarea"
                readonly
                rows="3"
                :placeholder="field.name"
            ></textarea>
        </div>
    </field-wrap>
</template>

<script>
    import fieldMixin from '@js/mixins/field';

    export default {
        mixins: [fieldMixin],

        data() {
            return {
                field: {
                    id: null,
                    temp: false,
                    type_id: null,
                    name: '',
                    label: '',
                    required: false
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
                    required: values.required
                };
            }
        }
    }
</script>
