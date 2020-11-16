<template>
    <form @submit.prevent="submit">
        <form-field v-if="errorMessage">
            <div class="bg-red-500 text-white p-4 rounded">
                {{ errorMessage }}
            </div>
        </form-field>

        <div class="mb-4">            
            <div class="-m-2 md:flex md:flex-wrap">
                <div class="p-2 md:w-2/3">
                    <form-field input="cardNumber" label="Card number" required>
                        <div class="control control-icon-right">
                            <div id="cardNumber"></div>

                            <div class="icon">
                                <icon :icon="cardIcon"></icon>
                            </div>
                        </div>
                    </form-field>
                </div>

                <div class="p-2 md:w-1/3">
                    <form-field input="cardExpiry" label="Expiry date" required>
                        <div id="cardExpiry"></div>
                    </form-field>
                </div>

                <div class="p-2 md:w-1/3">
                    <form-field input="cardCvc" label="CVC number" required>
                        <div id="cardCvc"></div>
                    </form-field>
                </div>

                <div class="p-2 md:w-1/3">
                    <form-field input="Postcode" label="ZIP / Postal code" required>
                        <vue-input
                            id="postcode"
                            v-model="postcode"
                            required
                            placeholder="ZIP / Postal code"
                        ></vue-input>
                    </form-field>
                </div>

                <div class="p-2 md:flex md:items-end md:w-1/3">
                    <button
                        class="button w-full"
                        :class="{ 'loading': anyProcessing }"
                        :disabled="anyProcessing || anyErrors"
                    >{{ buttonText }}</button>
                </div>
            </div>
        </div>
    </form>
</template>

<script>
    import { mapActions, mapGetters } from 'vuex';
    import { theme } from '../../../../sass/tailwind.config';

    import FormField from '@js/components/form/Field';
    import VueInput from '@js/components/form/Input';

    export default {
        components: {
            FormField,
            VueInput
        },

        props: {
            processing: {
                type: Boolean,
                default: false
            },

            buttonText: {
                type: String,
                default: 'Subscribe'
            }
        },

        data() {
            return {
                stripe: null,

                elements: {
                    cardNumber: null,
                    cardExpiry: null,
                    cardCvc: null
                },

                postcode: '',

                errorMessage: null,
                isProcessing: false,

                cardIcon: ['far', 'credit-card'],
                cardBrands: {
                    visa: ['fab', 'cc-visa'],
                    mastercard: ['fab', 'cc-mastercard'],
                    amex: ['fab', 'cc-amex'],
                    discover: ['fab', 'cc-discover'],
                    diners: ['fab', 'cc-diners-club'],
                    jcb: ['fab', 'cc-jcb'],
                    unknown: ['far', 'credit-card']
                }
            };
        },

        computed: {
            ...mapGetters({
                anyErrors: 'errors/any'
            }),

            anyProcessing() {
                return this.processing || this.isProcessing;
            }
        },

        mounted() {
            this.stripe = Stripe('pk_test_HX5FJ1qM1BUwJovqUzb4j9wQ00NTZD0GK3');
    
            let elements = this.stripe.elements({
                fonts: [{
                    cssSrc: 'https://fonts.googleapis.com/css?family=Source+Sans+Pro:400'
                }]
            });

            Object.keys(this.elements).forEach(elementName => {
                this.elements[elementName] = elements.create(elementName, {
                    style: {
                        base: {
                            fontWeight: 400,
                            fontSize: '14px',
                            lineHeight: '38px',
                            fontSmoothing: 'antialiased',
                            color: theme.colors.grey['700'],
                            fontFamily: theme.fontFamily.sans[0],

                            '::placeholder': {
                                color: theme.colors.grey['500'],
                            }
                        }
                    },
                    classes: {
                        base: 'stripe-input'
                    }
                });

                this.elements[elementName].mount(`#${elementName}`);

                this.elements[elementName].on('change', event => {
                    if (event.brand && this.cardBrands[event.brand]) {
                        this.cardIcon = this.cardBrands[event.brand];
                    }

                    if (event.error) {
                        this.setError({
                            key: elementName,
                            value: event.error.message
                        });
                    } else {
                        this.clearError(elementName)
                    }
                });
            });
        },

        methods: {
            ...mapActions({
                setError: 'errors/setSingle',
                clearError: 'errors/clearSingle'
            }),

            submit() {
                if (! this.anyErrors && this.postcode) {
                    this.isProcessing = true;
                    this.errorMessage = null;
    
                    this.stripe.createToken(this.elements.cardNumber, {
                        address_zip: this.postcode
                    }).then(result => {
                        if (result.error) {
                            this.errorMessage = result.error.message;
                        } else {
                            this.$emit('success', result);   
                        }

                        this.isProcessing = false;                         
                    });
                }
            }
        }
    }
</script>
