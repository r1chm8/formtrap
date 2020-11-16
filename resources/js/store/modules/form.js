import router from '../../router';

const state = {
    data: {},
    fields: [],

    activeFieldId: null,
    activeFieldErrors: {},
    selectedFieldId: null,

    fieldTransition: 'none',
    fieldTypes: [],
};

const getters = {
    data: state => state.data,

    fields: state => state.fields,

    field: (state, getters) => fieldId => {
        let index = getters.fieldIndex(fieldId);

        return state.fields[index];
    },

    activeFieldId: state => state.activeFieldId,

    activeFieldErrors: state => state.activeFieldErrors,

    selectedFieldId: state => state.selectedFieldId,

    fieldIndex: state => fieldId => {
        return state.fields.findIndex(({ id }) => {
            return fieldId === id;
        });
    },

    fieldTypes: state => state.fieldTypes,

    fieldTransition: state => state.fieldTransition,

    canMoveFieldUp: (state, getters) => fieldId => {
        let index = getters.fieldIndex(fieldId);

        return index > 0;
    },

    canMoveFieldDown: (state, getters) => fieldId => {
        let index = getters.fieldIndex(fieldId);

        return (index + 1) < state.fields.length;
    },

    uniqueFieldId: state => {
        let ids = state.fields
            .map(({ id }) => id)
            .sort((a, b) => a - b);

        return ids.length
            ? ids.slice(-1)[0] + 1
            : 1;
    }
};

const mutations = {
    setData(state, data) {
        state.data = data;
    },

    updateData(state, data) {
        state.data = {
            ...state.data,
            ...data
        };
    },

    setFields(state, fields) {
        state.fields = fields;
    },

    insertField(state, { field, index }) {
        state.fields.splice(index, 0, { ...field });
    },

    updateField(state, { field, index }) {
        state.fields.splice(index, 1, { ...field });
    },

    removeField(state, fieldId) {
        state.fields = state.fields.filter(({ id }) => {
            return fieldId !== id;
        });
    },

    setActiveFieldId(state, id) {
        state.activeFieldId = id;
    },

    clearActiveFieldErrors(state) {
        state.activeFieldErrors = {};
    },

    setActiveFieldErrors(state, errors) {
        state.activeFieldErrors = errors;
    },

    setSelectedFieldId(state, id) {
        state.selectedFieldId = id;
    },

    moveField(state, { from, to }) {
        state.fields.splice(to, 0, state.fields.splice(from, 1)[0]);
    },

    setFieldTypes(state, types) {
        state.fieldTypes = types;
    },

    setFieldTransition(state, transition) {
        state.fieldTransition = transition;
    }
};

const actions = {
    fetchData({ commit, dispatch, getters, rootGetters }, formId) {
        let form = getters.data;
        
        if (
            (! form.hasOwnProperty('id') || form.id !== formId)
            && ! rootGetters['loader/isLoading']('primary.form')
        ) {
            dispatch('loader/startLoading', 'primary.form', { root: true });
            
            axios.get('/api/forms/' + formId)
                .then(response => {
                    commit('setData', response.data.data);

                    dispatch('loader/stopLoading', 'primary.form', { root: true });
                })
                .catch(error => {
                    if (error.response.status.toString().substring(0, 2) === '40') {
                        router.push({ name: 'forms.index' }, () => {
                            Vue.toasted.error('An unexpected error occured.', {
                                duration: 3000
                            });
                        });
                    }
                });
        }
    },

    fetchFields({ commit, dispatch }, { formId, versionId }) {
        dispatch('loader/startLoading', 'primary.form-fields', { root: true });

        return axios.get(`/api/forms/${formId}/versions/${versionId}/fields`)
            .then(response => {
                commit('setFields', response.data.data.map(field => {
                    field.type_id = field.type.id;
                    delete field.type;

                    return field;
                }));
            })
            .catch(error => {
                if (error.response.status.toString().substring(0, 2) === '40') {
                    router.push({ name: 'forms.index' }, () => {
                        Vue.toasted.error('An unexpected error occured.', {
                            duration: 3000
                        });
                    });
                }
            })
            .finally(() => {
                dispatch('loader/stopLoading', 'primary.form-fields', { root: true });
            });
    },

    fetchFieldTypes({ commit, dispatch, getters }) {
        if (getters.fieldTypes.length === 0) {
            dispatch('loader/startLoading', 'primary.field-types', { root: true });

            axios.get('/api/field-types').then(response => {
                commit('setFieldTypes', response.data.data);

                dispatch('loader/stopLoading', 'primary.field-types', { root: true });
            });
        }
    },

    addField({ commit, getters }, field) {
        commit('insertField', {
            field,
            index: getters.fields.length
        });

        commit('setFieldTransition', 'none');
        commit('setActiveFieldId', field.id);
        commit('setSelectedFieldId', field.id);

        return field.id;
    },

    updateField({ commit, getters }, field) {
        let fieldIndex = getters.fieldIndex(field.id);

        commit('updateField', {
            field,
            index: fieldIndex
        });
    },

    moveFieldUp({ commit, getters }, fieldId) {
        if (getters.canMoveFieldUp(fieldId)) {
            let fieldIndex = getters.fieldIndex(fieldId);

            Vue.toasted.clear();
            commit('setFieldTransition', 'flip-list');

            commit('moveField', {
                from: fieldIndex,
                to: fieldIndex - 1
            });
        }
    },

    moveFieldDown({ commit, getters }, fieldId) {
        if (getters.canMoveFieldDown(fieldId)) {
            let fieldIndex = getters.fieldIndex(fieldId);

            Vue.toasted.clear();
            commit('setFieldTransition', 'flip-list');

            commit('moveField', {
                from: fieldIndex,
                to: fieldIndex + 1
            });
        }
    },

    reset({ commit }) {
        commit('setActiveFieldId', null);
        commit('setSelectedFieldId', null);
        commit('setFieldTransition', 'none');
    }
};

export default {
    namespaced: true,
    state,
    getters,
    mutations,
    actions
};
