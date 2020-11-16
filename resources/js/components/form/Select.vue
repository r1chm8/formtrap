<template>
    <div class="control">
        <div class="select w-full">
            <select :id="id" v-model="newValue" :required="required">
                <slot></slot>
            </select>
        </div>
    </div>
</template>

<script>
    import inputMixin from '@js/mixins/input';

    export default {
        mixins: [ inputMixin ]
    }
</script>
