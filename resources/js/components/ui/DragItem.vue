<template>
    <div>
        <slot></slot>
    </div>
</template>

<script>
    import { ElementMixin } from 'vue-slicksort';

    export default {
        mixins: [ ElementMixin ]
    }
</script>
