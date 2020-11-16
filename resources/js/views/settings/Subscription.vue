<template>
    <div class="relative">
        <div v-if="! isLoading()">
            <div class="bg-white shadow px-4 py-6 md:px-8 md:pt-6 md:pb-8">
                <h1 class="subtitle mb-4">Subscription plans</h1>

                <p v-if="subscription" class="mb-6">
                    Your current plan: <strong>{{ subscription.name }}</strong>
                </p>

                <div
                    v-if="errorMessage"
                    class="bg-red-500 text-white p-6 mb-6"
                >{{ errorMessage }}</div>

                <div class="plans mb-8 md:mb-12">
                    <a
                        :key="plan.id"
                        v-for="plan in plans"
                        class="plan"
                        :class="{ 'active': plan_id === plan.id }"
                        @click="plan_id = plan.id"
                    >
                        <strong class="text-lg -mb-1">
                            {{ plan.label }}
                        </strong>

                        <span>
                            <span class="text-xl">
                                £{{ plan.price }}
                            </span><span class="-ml-1 text-xs">
                                /mo
                            </span>
                        </span>

                        <span v-if="plan_id === plan.id" class="icon text-white">
                            <icon icon="check" size="xs"></icon>
                        </span>

                        <span v-if="plan.id === 'premium'" class="plan-banner">
                            Most popular
                        </span>
                    </a>
                </div>

                <template v-if="hasSubscription">
                    <button
                        class="button"
                        :class="{ 'loading': changingPlan }"
                        :disabled="changingPlan || subscription.plan_id === plan_id"
                        @click="changePlan"
                    >Change Subscription</button>
                </template>

                <payment-form
                    v-else
                    :processing="processingPayment"
                    @success="processPayment"
                ></payment-form>
            </div>

            <template v-if="hasSubscription">
                <div class="bg-white shadow my-8 px-4 py-6 md:px-8 md:pt-6 md:pb-8">
                    <h2 class="subtitle mb-6">Update credit card</h2>

                    <payment-form
                        button-text="Update card"
                        :processing="updatingCard"
                        @success="updateCard"
                    ></payment-form>
                </div>

                <div class="bg-white shadow px-4 py-6 md:px-8 md:pt-6 md:pb-8">
                    <h2 class="subtitle mb-4">Cancel subscription</h2>

                    <p class="mb-6">
                        If you choose to cancel your subscription you will lose access to all paid for features
                        including ...
                    </p>

                    <a
                        class="button red-500"
                        @click="openConfirmation"
                    >Cancel</a>
                </div>
            </template>
        </div>

        <loader class="bg-grey-200 -mx-1"></loader>

        <confirmation
            @confirm="cancel"
            button-class="red-500"
            button-text="Cancel plan"
            button-cancel-text="Continue plan"
        >Are you sure you want to cancel your subscription?</confirmation>
    </div>
</template>

<script>
    // Show subscription renewal date
    // Update Card / Show card number

    import { mapActions, mapGetters } from 'vuex';

    import Confirmation from '@js/components/ui/confirmation';
    import PaymentForm from './partials/PaymentForm';
    import FormField from '@js/components/form/Field';
    import VueInput from '@js/components/form/Input';

    export default {
        components: {
            Confirmation,
            PaymentForm,
            FormField,
            VueInput
        },

        data() {
            return {
                plans: [
                    { id: 'basic', label: 'Basic', price: 5 },
                    { id: 'premium', label: 'Premium', price: 10 },
                    { id: 'enterprise', label: 'Enterprise', price: 20 }
                ],
                plan_id: 'premium',

                subscription: {},

                processingPayment: false,
                changingPlan: false,
                updatingCard: false,

                errorMessage: ''
            }
        },

        computed: {
            ...mapGetters({
                isLoading: 'loader/isLoading'
            }),

            hasSubscription() {
                return !! Object.keys(this.subscription).length;
            }
        },

        created() {
            this.fetchSubscription();
        },

        methods: {
            ...mapActions({
                openConfirmation: 'confirmation/open'
            }),

            fetchSubscription() {
                this.startLoading('primary.subscription');

                // TODO
                setTimeout(() => {
                    this.subscription = {
                        plan_id: 'premium',
                        name: 'Premium'
                    }

                    this.stopLoading('primary.subscription');
                }, 250);

                // axios.get('/api/subscription').then(response => {
                //     this.subscription = response.data.data;

                //     this.stopLoading('primary.subscription');
                // });
            },

            processPayment(result) {
                this.processingPayment = true;

                axios.put('/api/subscription', {
                    plan_id: this.plan_id,
                    token_id: result.token.id
                }).then(response => {
                    let subscriptionStatus = response.data.status;
                    let paymentIntent = response.data.latest_invoice.payment_intent;

                    // Payment succeeds
                    if (subscriptionStatus === 'active' && paymentIntent.status === 'succeeded') {
                        this.processingPayment = false;
                        this.paymentComplete();
                    }

                    // Payment fails
                    if (subscriptionStatus === 'incomplete' && paymentIntent.status === 'requires_payment_method') {
                        this.processingPayment = false;
                        this.errorMessage = 'Payment failed, please try a different card.';
                    }

                    // Payment requires customer action
                    if (subscriptionStatus === 'incomplete' && paymentIntent.status === 'requires_action') {
                        stripe.handleCardPayment(
                            paymentIntent.client_secret
                        ).then(result => {
                            if (result.error) {
                                // TODO handle errors better - https://stripe.com/docs/api/errors
                                this.errorMessage = 'Payment failed.';
                                this.processingPayment = false;
                            } else {
                                axios.put('/api/subscriptions/verify', {
                                    id: result.paymentIntent.id,
                                    client_secret: result.paymentIntent.client_secret
                                }).then(response => {
                                    this.paymentComplete();
                                }).catch(error => {
                                    // TODO handle error
                                    console.log(error);
                                }).finally(() => {
                                    this.processingPayment = false;
                                });
                            }
                        });
                    }
                }).catch(error => {
                    this.processingPayment = false;

                    // TODO handle error
                    console.log(error);
                    // if (error.response.status === 422) {
                    //     this.setErrors(error.response.data.errors);
                    // } else {
                    //     this.$toasted.error('An unexpected error occured.');
                    // }
                });
            },

            paymentComplete() {
                console.log('payment complete, what now?');
            },

            changePlan() {
                this.changingPlan = true;

                // TODO
                setTimeout(() => {
                    this.changingPlan = false;
                }, 1000);
            },

            updateCard() {
                this.updatingCard = true;

                // TODO
                setTimeout(() => {
                    this.updatingCard = false;
                }, 1000);
            },

            cancel() {
                // TODO
                console.log('cancel');
            }
        }
    }
</script>
