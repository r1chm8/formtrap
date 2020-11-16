<template>
    <div class="flex flex-col flex-grow">
        <sub-header>
            <h1 class="title">Enquiry details</h1>
        </sub-header>

        <div class="relative container mx-auto px-4 py-8 md:px-8">
            <div v-if="enquiry" class="bg-white shadow mb-8">
                <div class="flex items-center py-2 px-2 border-b border-grey-400 md:px-4">
                    <ul class="action-bar">
                        <li>
                            <router-link
                                :to="{ name: 'enquiries.index' }"
                                class="action-bar-item"
                                title="Back"
                            >
                                <span class="icon">
                                    <icon icon="arrow-left"></icon>
                                </span>
                            </router-link>
                        </li>

                        <li>
                            <a
                                class="action-bar-item"
                                title="Mark as unread"
                                @click="markAsUnread"
                                :class="{
                                    'pointer-events-none opacity-25': isLoading('change-enquiry-status')
                                }"
                            >
                                <span class="icon">
                                    <icon icon="envelope"></icon>
                                </span>
                            </a>
                        </li>

                        <li>
                            <a
                                class="action-bar-item"
                                title="Delete enquiry"
                                @click="openConfirmation"
                                :class="{ 'pointer-events-none': isLoading('deleting-enquiry') }"
                            >
                                <span class="icon">
                                    <icon
                                        :icon="isLoading('deleting-enquiry') ? 'spinner' : 'trash-alt'"
                                        :spin="isLoading('deleting-enquiry')"
                                    ></icon>
                                </span>
                            </a>
                        </li>
                    </ul>
                </div>

                <div class="px-4 py-6 md:p-8">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-lg">
                            {{ enquiry.form.name }}
                        </h2>

                        <h3 class="ml-1 text-xs font-normal">
                            <span class="lg:hidden">
                                {{ mobileDate }}
                            </span>

                            <span class="hidden lg:block">
                                {{ desktopDate }}
                            </span>
                        </h3>
                    </div>

                    <div class="content whitespace-pre-line">
                        <p :key="content.id" v-for="content in enquiry.contents">
                            <strong>{{ content.key || 'Unknown field' }}:</strong>
                            {{ formatValue(content.value) }}
                        </p>
                    </div>
                </div>
            </div>

            <loader></loader>
        </div>

        <confirmation
            @confirm="remove"
            button-class="red"
            button-text="Delete"
        >Are you sure you want to delete this enquiry?</confirmation>
    </div>
</template>

<script>
    import { mapActions, mapGetters } from 'vuex';
    import moment from 'moment';

    import Confirmation from '@js/components/ui/confirmation';

    export default {
        components: { Confirmation },

        data() {
            return {
                enquiry: null
            }
        },

        computed: {
            ...mapGetters({
                isLoading: 'loader/isLoading'
            }),

            mobileDate() {
                let enquriyAge = moment.duration(moment(new Date())
                    .diff(this.enquiry.created_at))
                    .asDays();

                if (enquriyAge > 1) {
                    return moment.utc(this.enquiry.created_at).local().format('Do MMM YYYY');
                }

                return moment.utc(this.enquiry.created_at).local().format('H:mm:ss a');
            },

            desktopDate() {
                return moment.utc(this.enquiry.created_at).local().format('MMMM Do, YYYY, h:mm:ss a')
                    + ' (' + moment.utc(this.enquiry.created_at).local().fromNow() + ')';
            }
        },

        created() {
            this.fetchEnquiry();
            this.changeReadStatus('read');
        },

        methods: {
            ...mapActions({
                openConfirmation: 'confirmation/open'
            }),

            fetchEnquiry() {
                this.startLoading('primary.enquiry');

                axios.get('/api/enquiries/' + this.$route.params.id)
                    .then(response => {
                        this.enquiry = response.data.data;

                        this.stopLoading('primary.enquiry');
                    })
                    .catch(error => {
                        if (error.response.status.toString().substring(0, 2) === '40') {
                            this.$router.push({ name: 'enquiries.index' }, () => {
                                this.$toasted.error('An unexpected error occured.', {
                                    duration: 3000
                                });
                            });
                        }
                    });
            },

            formatValue(value) {
                if (value) {
                    return Array.isArray(value) 
                        ? value.join(', ')
                        : value;
                }

                return '-';
            },

            changeReadStatus(status) {
                this.startLoading('change-enquiry-status');

                return axios.patch('/api/enquiries/' + this.$route.params.id, {
                    read: status === 'read'
                }).then(response => {
                    this.stopLoading('change-enquiry-status');
                });
            },

            markAsUnread() {
                this.changeReadStatus('unread').then(() => {
                    this.$router.push({ name: 'enquiries.index' });
                });
            },

            remove() {
                this.startLoading('deleting-enquiry');

                axios.delete('/api/enquiries/' + this.$route.params.id).then(() => {
                    this.stopLoading('deleting-enquiry');    
                    this.$toasted.success('Enquiry successfully deleted.');

                    this.$router.push({ name: 'enquiries.index' });
                });
            }
        }
    }
</script>
