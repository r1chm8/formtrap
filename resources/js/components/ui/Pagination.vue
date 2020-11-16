<template>
    <nav class="pagination is-centered" v-if="isVisible">
        <a
            class="pagination-previous"
            :disabled="! hasPreviousPage"
            @click="changePage(previousPage)"
        >Prev</a>

        <a
            class="pagination-next"
            :disabled="! hasNextPage"
            @click="changePage(nextPage)"
        >Next</a>

        <ul class="pagination-list">
            <li :key="page" v-for="page in pages">
                <span
                    v-if="page === seperator"
                    class="pagination-ellipsis"
                >{{ seperator }}</span>

                <a
                    class="pagination-link"
                    v-else
                    @click="changePage(page + 1)"
                    :class="{ 'current': page === currentPage }"
                >{{ page + 1 }}</a>
            </li>
        </ul>
    </nav>
</template>

<script>
    export default {
        props: {
            options: {
                type: Object,
                default: null
            },

            visiblePages: {
                type: Number,
                default: 3
            },

            seperator: {
                type: String,
                default: '...'
            }
        },
        
        computed: {
            isVisible() {
                return this.options && this.options.last_page > 1;
            },

            currentPage() {
                return this.options ? this.options.current_page - 1 : 0;
            },

            previousPage() {
                return this.options.current_page - 1;
            },

            nextPage() {
                return this.options.current_page + 1;
            },

            hasPreviousPage() {
                return this.previousPage > 0;
            },

            hasNextPage() {
                return this.nextPage <= this.options.last_page;
            },

            pages() {
                let pages;
                
                if (this.filteredPages) {
                    pages = [
                        this.filteredPages[0] - 1 === 1 ? 1 : this.seperator,

                        ...this.filteredPages,

                        this.filteredPages[this.filteredPages.length - 1] + 1 === this.options.last_page - 2
                            ? this.options.last_page - 2
                            : this.seperator
                    ];
                } else {
                    pages = [ ...Array(this.options.last_page - 2).keys() ].map(page => {
                        return page + 1
                    });
                }

                return [
                    0,
                    ...pages,
                    this.options.last_page - 1
                ];
            },

            filteredPages() {
                let toFilterPages = [...Array(this.options.last_page).keys()].slice(2, -2);

                if (this.visiblePages > toFilterPages.length) {
                    return null;
                }

                let diff = this.visiblePages / 2;
                let diffFirst = this.currentPage - toFilterPages[0];
                let diffLast = this.currentPage - toFilterPages[toFilterPages.length - 1];

                if (diffFirst < diff) {
                    return toFilterPages.slice(0, this.visiblePages);
                }
                
                if (diffLast >= -diff) {
                    return toFilterPages.slice(-this.visiblePages);
                }

                return toFilterPages.filter(page => {
                    let diffPage = this.currentPage - page;

                    return diffPage < 0
                        ? Math.abs(diffPage) <= diff
                        : diffPage < diff;
                });
            }
        },

        methods: {
            changePage(page) {
                if (
                    page < 1
                    || page > this.options.last_page
                    || page === this.options.current_page
                ) {
                    return;
                }

                this.options.current_page = page;
                this.$emit('change-page', page);
            }
        }
    }
</script>
