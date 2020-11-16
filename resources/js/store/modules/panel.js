const state = {
    rightIsOpen: false
};

const getters = {
    rightIsOpen: state => state.rightIsOpen
};

const mutations = {
    openRight(state) {
        state.rightIsOpen = true;
    },

    closeRight(state) {
        state.rightIsOpen = false;
    }
};

const actions = {
    openRight({ commit }) {
        commit('openRight');
    },

    closeRight({ commit }) {
        commit('closeRight');
    }
};

export default {
    namespaced: true,
    state,
    getters,
    mutations,
    actions
};
