import { isEqual } from 'lodash';
import FieldWrap from '@js/views/forms/partials/fields/Wrap';

export default {
    components: { FieldWrap },

    props: {
        value: {
            type: Object,
            default: () => { }
        }
    },

    data() {
        return {
            field: {}
        }
    },

    watch: {
        value: {
            handler(value) {
                if (! isEqual(this.field, value)) {
                    this.setValues(value);
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
        }
    },

    methods: {
        setValues() {
            //
        }
    }
}
