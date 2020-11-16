<template>
    <input
        :id="id"
        :type="type"
        class="input"
        :class="{ 'error': ! hideError && hasError(id) }"
        v-model="newValue"
    >
</template>

<script>
    import { mapGetters } from 'vuex';
    
    export default {
        props: {
            value: {
                default: null
            },

            id: {
                type: String,
                required: true
            },

            type: {
                type: String,
                default: 'text'
            },

            hideError: {
                type: Boolean,
                default: false
            }
        },

        data() {
            return {
                newValue: this.value
            }
        },

        computed: {
            ...mapGetters({
                hasError: 'errors/contains'
            })
        },

        watch: {
            value(value) {
                this.newValue = value;
            },

            newValue(value) {
                this.$emit('input', value);
            }
        }
    }
</script>
