<template>
    <div>
        <transition name="panel-overlay">
            <div v-if="isOpen" class="panel-overlay" @click="close"></div>
        </transition>

        <transition name="panel-right">
            <div class="panel-right bg-white" v-if="isOpen">
                <div class="panel-content p-8">
                    <slot></slot>
                </div>
            </div>
        </transition>
    </div>
</template>

<script>
    import { mapActions, mapGetters } from 'vuex';
    
    export default {
        computed: {
            ...mapGetters({
                isOpen: 'panel/rightIsOpen'
            })
        },

        watch: {
            isOpen(open) {
                let classList = document.documentElement.classList;

                if (open) {
                    classList.add('overflow-hidden');
                } else {
                    classList.remove('overflow-hidden');
                }
            }
        },

        methods: {
            ...mapActions({
                close: 'panel/closeRight'
            })
        }
    }
</script>
