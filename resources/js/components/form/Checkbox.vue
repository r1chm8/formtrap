<template>
    <div class="control">
        <input
            :id="id"
            :name="id"
            :type="type"
            class="checkbox"
            :value="checkedValue"
            v-model="newValue"
        >

        <label :for="id">{{ label }}</label>
    </div>
</template>

<script>
    import inputMixin from '@js/mixins/input';

    export default {
        mixins: [ inputMixin ],
        
        props: {
            checkedValue: {
                default: true
            },

            label: {
                type: String,
                required: true
            },

            type: {
                type: String,
                default: 'checkbox'
            }
        }
    }
</script>
