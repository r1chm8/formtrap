const state = {
    list: {}
};

const getters = {
    list: state => {
        return state.list;
    },

    any: state => {
        return !! Object.keys(state.list).length;
    },

    contains: state => error => {
        return state.list.hasOwnProperty(error);
    },

    getMessage: (state, getters) => error => {
        return getters.contains(error)
            ? state.list[error][0]
            : null;
    }
};

const mutations = {
    set(state, errors) {
        state.list = errors;
    },

    setSingle(state, { key, value }) {
        state.list = { ...state.list, [key]: [value] }
    },

    clearSingle(state, key) {
        let list = state.list;
        delete list[key];

        state.list = list;
    }
};

const actions = {
    set({ commit }, errors) {
        commit('set', errors);
    },

    setSingle({ commit }, error) {
        commit('setSingle', error);
    },

    clearSingle({ commit }, error) {
        commit('clearSingle', error);
    },

    clear({ commit }) {
        commit('set', {});
    }
};

export default {
    namespaced: true,
    state,
    getters,
    mutations,
    actions
};
