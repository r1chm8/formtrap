<template>
    <div class="placeholder-input relative">
        <input
            type="text"
            @blur="$emit('blur')"
            class="input"
            :class="{ 'error': hasError }"
            v-model="newValue"
            :required="required"
            :autofocus="autofocus"
            placeholder=" "
        >

        <label :class="{ 'active': showPlaceholder }">
            {{ placeholder }}

            <span v-if="required" class="ml-1 text-red-lighter">*</span>
        </label>
    </div>
</template>

<script>
    export default {
        props :{
            value: {
                default: null
            },

            required: {
                type: Boolean,
                default: false
            },

            autofocus: {
                type: Boolean,
                default: false
            },

            placeholder: {
                type: String,
                required: true
            },

            showPlaceholder: {
                type: Boolean,
                default: false
            },

            hasError: {
                type: Boolean,
                default: false
            }
        },

        data() {
            return {
                newValue: this.value
            }
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
