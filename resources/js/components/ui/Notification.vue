<template>
    <transition name="fade">
        <div class="p-6">
            <a
                v-if="closeable"
                class="delete"
                @click="$emit('close')"
            ></a>

            <slot></slot>
        </div>
    </transition>
</template>

<script>
    export default {
        props: {
            closeable: {
                type: Boolean,
                default: true
            }
        }
    }
</script>
