<template>
    <div class="control control-icon-right">
        <input
            :id="id"
            :name="name"
            :type="type"
            class="input"
            :required="required"
            :input-class="inputClass"
            :placeholder="placeholder"
            :autocomplete="autocomplete"
        >
        
        <a class="icon" @click="toggle">
            <icon :icon="icon"></icon>
        </a>
    </div>
</template>

<script>
    export default {
        props: {
            id: String,
            name: String,

            required: {
                type: Boolean,
                default: false
            },

            inputClass: String,
            placeholder: String,
            autocomplete: String
        },
        
        data() {
            return {
                type: 'password'
            }
        },

        computed: {
            icon() {
                return this.type === 'password' ? 'eye' : 'eye-slash';
            }
        },

        methods: {
            toggle() {
                this.type = this.type === 'password' ? 'text' : 'password';
            }
        }
    }
</script>
