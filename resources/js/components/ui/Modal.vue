<template>
    <transition name="fade">
        <div class="modal active" v-if="active">
            <div class="modal-background" @click="$emit('close')"></div>
            
            <slot></slot>
        </div>
    </transition>
</template>

<script>
    export default {
        props: {
            active: {
                type: Boolean,
                required: true
            }
        },

        created() {
            document.addEventListener('keydown', this.onEscape);
        },

        beforeDestroy() {
            document.removeEventListener('keydown', this.onEscape);
        },

        methods: {
            onEscape(event) {
                if (this.active && event.keyCode === 27) {
                    this.$emit('escape');
                }
            }
        }
    }
</script>
