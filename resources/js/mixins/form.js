import { mapActions } from 'vuex';

import FormField from '@js/components/form/Field';
import VueCheckbox from '@js/components/form/Checkbox';
import VueInput from '@js/components/form/Input';

export default {
    components: {
        FormField,
        VueCheckbox,
        VueInput
    },
    
    props: {
        method: {
            type: String,
            required: true
        },

        action: {
            type: String,
            required: true
        },

        item: {
            type: Object,
            default: null
        }
    },

    data() {
        return {
            isProcessing: false
        }
    },

    methods: {
        ...mapActions({
            setErrors: 'errors/set',
            clearErrors: 'errors/clear'
        }),

        submit() {
            this.isProcessing = true;

            axios[this.method](this.action, this.form)
                .then(response => {
                    this.clearErrors();
                    this.onSuccess(response);
                })
                .catch(error => {
                    if (error.response.status === 422) {
                        this.setErrors(error.response.data.errors);
                    } else {
                        this.$toasted.error('An unexpected error occured.');
                    }

                    this.onError(error);
                })
                .finally(() => {
                    this.isProcessing = false;

                    this.onFinally();
                });
        },

        onSuccess(response) {
            //
        },

        onError(error) {
            window.scroll(0, 0);
        },

        onFinally() {
            //
        }
    }
};