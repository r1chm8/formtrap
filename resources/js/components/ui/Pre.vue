<template>
    <div class="pre-holder">
        <template v-if="copyable">
            <a class="pre-copy" @click="copy">
                Copy
            </a>

            <textarea
                ref="code"
                class="absolute pin-hidden"
                :value="$slots.default[0].text"
            ></textarea>
        </template>
        
        <pre><slot></slot></pre>
    </div>
</template>

<script>
    export default {
        props: {
            copyable: {
                type: Boolean,
                default: true
            }
        },

        methods: {
            copy() {
                this.$refs.code.select();
                document.execCommand('copy');

                this.$toasted.success('Code successfully copied', {
                    duration: 1500
                });
            }
        }
    }
</script>
