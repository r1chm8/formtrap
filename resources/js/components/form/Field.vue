<template>
    <div class="field" :class="{ 'required': required }">
        <label :for="input" class="label" v-if="label">{{ label }}</label>

        <div class="control">
            <slot></slot>
        </div>

        <div class="help" v-if="$slots.hasOwnProperty('help')">
            <slot name="help"></slot>
        </div>

        <div class="error-message" v-if="! hideError && hasError(input)">
            {{ getErrorMessage(input) }}
        </div>
    </div>
</template>

<script>
    import { mapGetters } from 'vuex';

    export default {
        props: {
            label: String,
            input: String,

            required: {
                type: Boolean,
                default: false
            },

            hideError: {
                type: Boolean,
                default: false
            }
        },

        computed: {
            ...mapGetters({
                hasError: 'errors/contains',
                getErrorMessage: 'errors/getMessage'
            })
        }
    }
</script>
