<template>
    <div>
        <slot name="tabs" :tabs="tabs">
            <ul class="tabs">
                <li
                    :key="i"
                    v-for="(tab, i) in tabs"
                    :class="{ 'active': tab.isActive }"
                >
                    <a @click="selectTab(tab.id)">
                        {{ tab.name }}
                    </a>
                </li>
            </ul>
        </slot>

        <slot></slot>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                tabs: []
            }
        },

        computed: {
            firstTab() {
                return this.tabs[0];
            }
        },

        created() {
            this.tabs = this.$children;
        },

        mounted() {
            if (this.tabs.length) {
                this.selectTab(this.firstTab.id);
            }
        },

        methods: {
            findTab(id) {
                return this.tabs.find(tab => tab.id === id);
            },

            selectTab(selectedId) {
                let selectedTab = this.findTab(selectedId);

                if (selectedTab) {
                    this.tabs.forEach(tab => {
                        tab.isActive = tab.id === selectedTab.id;
                    });

                    this.activeTabId = selectedTab.Id;
                }
            }
        }
    }
</script>
