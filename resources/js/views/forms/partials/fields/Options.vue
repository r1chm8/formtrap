<template>
    <div class="-mx-3 flex pt-6">
        <div class="px-3 w-full md:w-3/4">
            <drag-list
                id="option-list"
                class="option-list"
                :class="{ 'dragging': isDragging }"
                v-model="options"
                lockAxis="y"
                append-to="#option-list"
                @sort-end="isDragging = false"
                @sort-start="isDragging = true"
                :lockOffset="['0%', '0%']"
                helper-class="dragging"
                useDragHandle
                lockToContainerEdges
            >
                <field-option
                    :ref="option.id"
                    :key="option.id"
                    v-for="(option, index) in options"
                    :item="option"
                    :index="index"
                    @input="value => updateOption(index, value)"
                    @remove="removeOption"
                    :deletable="options.length > 1"
                ></field-option>
            </drag-list>

            <a class="inline-flex items-center pl-5" @click="add">
                <span class="icon mr-1">
                    <icon icon="plus"></icon>
                </span>

                <span>Add option</span>
            </a>
        </div>
    </div>
</template>

<script>
    // todo get error messages

    import { mapGetters } from 'vuex';
    import { cloneDeep, isEqual } from 'lodash';
    import DragList from '@js/components/ui/DragList';
    import FieldOption from './Option';

    export default {
        props: {
            value: {
                type: Array,
                default: () => []
            }
        },

        components: {
            DragList,
            FieldOption
        },

        data() {
            return {
                isDragging: false,
                options: []
            }
        },

        watch: {
            value: {
                handler(value) {
                    if (! isEqual(value, this.options)) {
                        this.options = cloneDeep(value);
                    }
                },
                deep: true
            },

            options: {
                handler(options) {
                    this.$emit('input', options);
                },
                deep: true
            }
        },

        created() {
            this.options = this.value.map((option, index) => {
                return { ...option, id: index + 1 };
            })
        },

        methods: {
            add() {
                let id = this.getUniqueId();

                this.options.push({
                    id,
                    value: '',
                    label: ''
                });

                this.$nextTick(() => {
                    this.$refs[id][0].$el.getElementsByTagName('input')[0].focus();
                });
            },

            updateOption(index, option) {
                this.options.splice(index, 1, option);
            },

            removeOption(optionId) {
                this.options = this.options.filter(({ id }) => {
                    return optionId !== id
                });
            },

            getUniqueId() {
                let ids = this.options
                    .map(({ id }) => id)
                    .sort((a, b) => a - b);

                return ids.length
                    ? ids.slice(-1)[0] + 1
                    : 1;
            }
        }
    }
</script>
