<template>
    <drag-item class="option" :index="index">
        <div class="option-grab-handle" v-handle>
            <div class="icon">
                <icon icon="grip-vertical"></icon>
            </div>
        </div>

        <div class="flex-grow px-1">
            <placeholder-input
                ref="input"
                v-model="option.label"
                required
                :has-error="hasErrorMessage('label')"
                placeholder="Option label"
                :show-placeholder="index === 0"
            ></placeholder-input>

            <div class="error-message" v-if="hasErrorMessage('label')">
                {{ getErrorMessage('label') }}
            </div>
        </div>

        <div v-if="valueIsVisible" class="flex-grow px-1">
            <placeholder-input
                v-model="option.value"
                :has-error="hasErrorMessage('value')"
                placeholder="Option value"
                :show-placeholder="index === 0"
            ></placeholder-input>

            <div class="error-message" v-if="hasErrorMessage('value')">
                {{ getErrorMessage('value') }}
            </div>
        </div>

        <div class="option-actions">
            <a
                class="icon text-grey-500 hover:text-grey-600 mr-1"
                @click="valueIsVisible = ! valueIsVisible"
                :class="{ 'pointer-events-none opacity-25': option.value }"
            >
                <icon icon="sliders-h"></icon>
            </a>

            <a
                class="icon text-grey-500 hover:text-grey-600"
                @click="$emit('remove', option.id)"
                :class="{ 'pointer-events-none opacity-25': ! deletable }"
            >
                <icon icon="trash-alt"></icon>
            </a>
        </div>
    </drag-item>
</template>

<script>
    import { mapGetters } from 'vuex';
    import { HandleDirective } from 'vue-slicksort';

    import DragItem from '@js/components/ui/DragItem';
    import Dropdown from '@js/components/ui/Dropdown';
    import PlaceholderInput from '@js/components/form/PlaceholderInput';

    export default {
        props: {
            index: Number,
            item: Object,

            deletable: {
                type: Boolean,
                default: false
            }
        },

        components: {
            DragItem,
            Dropdown,
            PlaceholderInput
        },

        directives: {
            handle: HandleDirective
        },

        data() {
            return {
                valueIsVisible: false,

                option: {
                    id: null,
                    label: '',
                    value: ''
                }
            }
        },

        computed: {
            ...mapGetters({
                activeFieldId: 'form/activeFieldId',
                fieldErrors: 'form/activeFieldErrors'
            }),

            errors() {
                let errors = this.fieldErrors.hasOwnProperty('options')
                    ? this.fieldErrors.options
                    : {};

                return errors.hasOwnProperty(this.option.id)
                    ? errors[this.option.id]
                    : {};
            }
        },

        watch: {
            option: {
                handler(option) {
                    this.$emit('input', option);
                },
                deep: true
            }
        },

        created() {
            this.option = {
                id: this.item.id,
                label: this.item.label,
                value: this.item.value
            };

            if (this.option.value) {
                this.valueIsVisible = true;
            }
        },

        methods: {
            hasErrorMessage(field) {
                return this.errors.hasOwnProperty(field);
            },

            getErrorMessage(field) {
                return this.errors.hasOwnProperty(field)
                    ? this.errors[field]
                    : null;
            }
        }
    }
</script>
