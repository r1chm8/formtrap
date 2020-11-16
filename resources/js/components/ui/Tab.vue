<template>
    <section v-show="isActive" :id="id">
        <slot></slot>
    </section>
</template>

<script>
    export default {
        props: {
            name: {
                required: true
            }
        },
        
        data() {
            return {
                isActive: false
            }
        },

        computed: {
            id() {
                return this.name.toLowerCase().replace(/ /g, '-');
            }
        }
    }
</script>
