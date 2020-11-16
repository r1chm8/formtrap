const state = {
    isOpen: false,
    item: {}
};

const getters = {
    isOpen: state => state.isOpen,

    item: state => state.item
};

const mutations = {
    setItem(state, item) {
        state.item = item;
    },

    open(state) {
        state.isOpen = true;
    },

    close(state) {
        state.isOpen = false;
    }
};

const actions = {
    open({ commit }, item = {}) {
        commit('setItem', item);
        commit('open');
    },

    close({ commit }) {
        commit('setItem', {});
        commit('close');
    }
};

export default {
    namespaced: true,
    state,
    getters,
    mutations,
    actions
};
