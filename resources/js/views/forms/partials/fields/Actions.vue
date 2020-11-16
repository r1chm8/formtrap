<template>
    <ul class="field-actions">
        <li>
            <a
                :class="{ 'opacity-25 cursor-auto': ! canMoveUp(fieldId) }"
                @click.stop="moveUp(fieldId)"
            >
                <span class="icon">
                    <icon icon="arrow-up"></icon>
                </span>
            </a>
        </li>

        <li>
            <a
                :class="{ 'opacity-25 cursor-auto': ! canMoveDown(fieldId) }"
                @click.stop="moveDown(fieldId)"
            >
                <span class="icon">
                    <icon icon="arrow-down"></icon>
                </span>
            </a>
        </li>

        <li>
            <a @click.stop="setActiveId(fieldId)">
                <span class="icon">
                    <icon icon="pencil-alt"></icon>
                </span>
            </a>
        </li>

        <li>
            <a @click.stop="remove(fieldId)">
                <span class="icon">
                    <icon icon="trash-alt"></icon>
                </span>
            </a>
        </li>
    </ul>
</template>

<script>
    import { mapActions, mapGetters, mapMutations } from 'vuex';

    export default {
        props: {
            versionUri: {
                type: String,
                required: true
            },

            fieldId: {
                type: Number,
                required: true
            }
        },

        computed: {
            ...mapGetters({
                canMoveUp: 'form/canMoveFieldUp',
                canMoveDown: 'form/canMoveFieldDown'
            }),
        },

        methods: {
            ...mapMutations({
                removeField: 'form/removeField',
                setActiveId: 'form/setActiveFieldId',
                setSelectedId: 'form/setSelectedFieldId',
                setTransition: 'form/setFieldTransition'
            }),

            ...mapActions({
                moveFieldUp: 'form/moveFieldUp',
                moveFieldDown: 'form/moveFieldDown'
            }),

            moveUp() {
                if (this.canMoveUp(this.fieldId)) {
                    this.moveFieldUp(this.fieldId);
                    
                    axios.put(`${this.versionUri}/${this.fieldId}/move`, {
                        direction: 'up'
                    });
                }
            },

            moveDown() {
                if (this.canMoveDown(this.fieldId)) {
                    this.moveFieldDown(this.fieldId);

                    axios.put(`${this.versionUri}/${this.fieldId}/move`, {
                        direction: 'down'
                    });
                }
            },

            remove() {
                axios.delete(`${this.versionUri}/${this.fieldId}`);

                this.setTransition('none');
                this.removeField(this.fieldId);

                this.setActiveId(null);
                this.setSelectedId(null);

                Vue.toasted.success('Field successfully removed.', {
                    duration: 3000,
                    action: [
                        {
                            text: 'Close',
                            onClick: (e, toastObject) => {
                                toastObject.goAway(0);
                            }
                        }
                    ]
                });
            }
        }
    }
</script>
