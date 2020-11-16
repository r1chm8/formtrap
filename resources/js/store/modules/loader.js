import matcher from 'matcher';

const state = {
    loading: []
};

const getters = {
    isLoading: state => item => {
        if (typeof item === 'string') {
            if (item.match(/[\*\!]/)) {
                return state.loading.filter(load => {
                    return matcher.isMatch(load, item)
                }).length > 0;
            }

            return state.loading.includes(item);
        }

        return state.loading.length > 0;
    }
};

const mutations = {
    start(state, item) {
        state.loading = [...new Set([
            ...state.loading,
            item
        ])];
    },

    stop(state, item) {
        if (typeof item === 'string') {
            if (item.match(/[\*\!]/)) {
                state.loading = state.loading.filter(load => {
                    return ! matcher.isMatch(load, item)
                });
            } else {
                let index = state.loading.indexOf(item);
                
                if (index !== -1) {
                    state.loading.splice(index, 1);
                }
            }
        } else {
            state.loading = [];
        }
    }
};

const actions = {
    startLoading({ commit }, item) {
        commit('start', item);
    },

    stopLoading({ commit }, item = null) {
        commit('stop', item);
    }
};

export default {
    namespaced: true,
    state,
    getters,
    mutations,
    actions
};
