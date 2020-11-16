export default {
    data() {
        return {
            filters: {},
            initialFilters: {}
        }
    },

    computed: {
        routeQuery() {
            return this.$route.query;
        },

        query() {
            let query = {};

            Object.keys(this.filters).forEach(key => {
                if (this.routeQuery[key]) {
                    query[key] = this.routeQuery[key];
                }
            });

            return query;
        }
    },

    watch: {
        query(query) {
            this.setFilters(query);
            this.onFilter(query);
        },

        filters: {
            handler(filters) {
                let query = {};

                Object.keys(filters).forEach(key => {
                    if (filters[key]) {
                        query[key] = filters[key];
                    }
                });

                this.$router.push({ query });
            },
            deep: true
        }
    },

    created() {
        this.initialFilters = Object.assign({}, this.filters);
        
        this.setFilters(this.query);
    },

    methods: {
        setFilters(query) {
            Object.keys(this.filters).forEach(key => {
                if (query.hasOwnProperty(key) && query[key]) {
                    this.filters[key] = query[key];
                } else {
                    this.filters[key] = this.initialFilters[key];
                }
            });
        },

        onFilter(query) {
            //
        }
    }
}
