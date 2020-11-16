<template>
    <div>
        <sub-header>
            <div class="flex items-center">
                <h1 class="title">
                    Enquiries<template v-if="forms.length">:</template>
                </h1>

                <dropdown
                    v-if="forms.length"
                    v-model="filters.form"
                    :options="forms.map(form => {
                        return {
                            value: form.id,
                            label: form.name
                        }
                    })"
                    placeholder="All forms"
                >
                    <a
                        slot="button"
                        class="flex items-center justify-center text-grey-600 ml-2 hover:text-grey-700"
                        slot-scope="{ props }"
                    >
                        <span class="title">{{ props.buttonText }}</span>

                        <span class="icon">
                            <icon icon="caret-down"></icon>
                        </span>
                    </a>
                </dropdown>
            </div>

        </sub-header>

        <div class="relative">
            <div v-if="! enquiries.length" class="flex flex-col flex-grow justify-center">
                <div class="max-w-2xl text-center mx-auto px-4 py-16 md:px-8">
                    <img src="/images/icons/enquiries.svg" alt="Enquiries" class="inline mb-8">

                    <h1 class="text-lg text-purple uppercase mb-2">No enquiries</h1>

                    <div class="mb-6">
                        <p>
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla eros libero, lobortis
                            bibendum, aliquet molestie dui. Curabitur bibendum erat id enim volutpat condimentum.
                        </p>
                    </div>
                </div>
            </div>

            <div v-if="enquiries.length" class="container mx-auto px-4 py-8 md:px-8">
                <div class="bg-white shadow">
                    <div class="flex items-center py-2 border-b border-grey-400">
                        <div class="cell flex-shrink-0 w-12 justify-center py-3">
                            <a class="p-3 -m-3" @click="toggleAll">
                                <span class="icon">
                                    <span
                                        class="select-box"
                                        :class="{ 'selected': allSelected }"
                                    ></span>
                                </span>
                            </a>
                        </div>

                        <div class="flex items-center">
                            <ul
                                class="action-bar"
                                :class="{ 'pointer-events-none opacity-25': ! selectedIds.length }"
                            >
                                <li>
                                    <a class="action-bar-item" title="Mark as read">
                                        <span class="icon">
                                            <icon icon="envelope-open"></icon>
                                        </span>
                                    </a>
                                </li>

                                <li>
                                    <a class="action-bar-item" title="Mark as unread">
                                        <span class="icon">
                                            <icon icon="envelope"></icon>
                                        </span>
                                    </a>
                                </li>

                                <li>
                                    <a class="action-bar-item" title="Delete">
                                        <span class="icon">
                                            <icon icon="trash-alt"></icon>
                                        </span>
                                    </a>
                                </li>
                            </ul>

                            <!-- <ul class="action-bar" v-else>
                                <li>
                                    <a title="Refresh" @click="refresh">
                                        <span class="icon">
                                            <icon icon="sync-alt" :spin="isRefeshing"></icon>
                                        </span>
                                    </a>
                                </li>
                            </ul> -->
                        </div>
                    </div>

                    <div class="table-style">
                        <div
                            :key="enquiry.id"
                            v-for="enquiry in enquiries"
                            class="tr cursor-pointer"
                            @click="showEnquiry(enquiry.id)"
                            :class="[ enquiry.read ? 'text-grey' : 'font-semibold' ]"
                        >
                            <div class="cell flex-shrink-0 w-12 justify-center py-3">
                                <a class="p-3 -m-3" @click.stop.prevent="toggleId(enquiry.id)">
                                    <span class="icon">
                                        <span
                                            class="select-box"
                                            :class="{ 'selected': selectedIds.includes(enquiry.id) }"
                                        ></span>
                                    </span>
                                </a>
                            </div>

                            <div class="flex flex-grow min-w-0 py-3 px-4 md:flex-no-wrap md:p-0">
                                <div class="flex flex-wrap w-1/2 md:flex-no-wrap">
                                    <div class="cell w-full flex-col items-start md:px-4 md:py-5">
                                        <div class="text-xs text-grey-500">
                                            {{ getFirstLabel(enquiry.contents) }}
                                        </div>

                                        <div class="truncate">
                                            {{ getFirstField(enquiry.contents) }}
                                        </div>
                                    </div>
                                </div>

                                <div class="flex flex-wrap w-1/2 pl-2 text-xs md:pl-0 md:flex-no-wrap md:text-sm">
                                    <div class="cell w-full justify-end md:auto md:flex-grow md:flex-col md:items-start md:px-4 md:py-5 lg:w-3/5">
                                        <div class="text-xs text-grey-500 hidden md:block">
                                            Form
                                        </div>

                                        <div class="truncate">
                                            {{ enquiry.form.name }}
                                        </div>
                                    </div>

                                    <div class="cell w-full justify-end md:w-32 md:flex-shrink-0 md:flex-col md:items-start md:px-4 md:py-5 lg:w-2/5">
                                        <div class="text-xs text-grey-500 hidden md:block">
                                            Date
                                        </div>

                                        <div class="truncate">
                                            {{ enquiry.created_at | formatDate }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- <div class="cell flex-shrink-0 w-8 justify-center px-1 py-3">
                                <a class="px-1 py-3 -mx-1 -my-3" @click.stop.prevent="">
                                    <span class="icon">
                                        <icon icon="envelope"></icon>
                                    </span>
                                </a>
                            </div>

                            <div class="cell flex-shrink-0 w-8 justify-center px-1 py-3 mr-2">
                                <a class="px-1 py-3 -mx-1 -my-3" @click.stop.prevent="">
                                    <span class="icon">
                                        <icon icon="trash-alt"></icon>
                                    </span>
                                </a>
                            </div> -->
                        </div>
                    </div>
                </div>

                <div v-if="pagination" class="pt-6">
                    <pagination
                        :options="pagination"
                        @change-page="value => filters.page = value"
                    ></pagination>
                </div>

                <div v-if="isRefeshing">
                    Refreshing enquiries
                </div>
            </div>

            <loader class="bg-grey-200"></loader>
        </div>
    </div>
</template>

<script>
    // todo
    // single actions on each enquiry, unread / delete
    // refresh listing
    // batch actions
    // pagination

    import moment from 'moment';
    import Dropdown from '@js/components/ui/Dropdown';
    import Pagination from '@js/components/ui/Pagination';
    import listingMixin from '@js/mixins/listing';

    export default {
        components: {
            Dropdown,
            Pagination
        },

        filters: {
            formatDate(date) {
                return moment.utc(date).local().fromNow();
            }
        },

        mixins: [ listingMixin ],

        data() {
            return {                
                forms: [],

                enquiries: [],
                pagination: [],

                selectedIds: [],

                filters: {
                    form: null,
                    page: null
                },

                isRefeshing: false
            }
        },

        computed: {
            allSelected() {
                return this.selectedIds.length
                    && this.selectedIds.length === this.enquiries.length;
            }
        },

        created() {
            this.startLoading('primary.enquiries');

            this.fetchEnquiries(this.query).then(() => {
                this.stopLoading('primary.enquiries');
            });

            this.fetchForms();
        },

        methods: {
            fetchEnquiries(queryParams = {}) {
                return axios.get('/api/enquiries', {
                    params: queryParams
                }).then(response => {
                    this.enquiries = response.data.data;
                    this.pagination = response.data.meta;
                });
            },

            fetchForms() {
                this.startLoading('primary.forms');

                axios.get('/api/forms').then(response => {
                    this.forms = response.data.data;
                
                    this.stopLoading('primary.forms');
                });
            },

            getFirstLabel(contents) {
                return (contents[0] && contents[0].hasOwnProperty('key') && contents[0].key)
                    ? contents[0].key
                    : '-';
            },

            getFirstField(contents) {
                let content = contents[0];

                if (content) {
                    let value = content.value;

                    return Array.isArray(value) 
                        ? value.join(', ')
                        : value;
                }

                return '-';
            },

            toggleAll() {
                if (this.allSelected) {
                    this.selectedIds = [];
                } else {
                    this.selectedIds = this.enquiries.map(({ id }) => id);
                }
            },

            toggleId(enquiryId) {
                if (this.selectedIds.includes(enquiryId)) {
                    this.selectedIds = this.selectedIds.filter(id => {
                        return id !== enquiryId;
                    })
                } else {
                    this.selectedIds.push(enquiryId);
                }
            },

            showEnquiry(enquiryId) {
                this.$router.push({ 
                    name: 'enquiries.show', 
                    params: { id: enquiryId } 
                })
            },

            onFilter(queryParams) {
                this.startLoading('secondary.enquiries');

                this.fetchEnquiries(queryParams).then(() => {
                    this.stopLoading('secondary.enquiries');
                });
            },

            refresh() {
                if (! this.isRefeshing) {
                    this.isRefeshing = true;
    
                    this.fetchEnquiries(this.query).then(() => {
                        this.isRefeshing = false;
                    });
                }
            }
        }
    }
</script>
