import axios from 'axios';

const state = {
    data: {}
};

const getters = {
    data: state => state.data
};

const mutations = {
    set(state, data) {
        state.data = data;
    },

    update(state, data) {
        state.data = { ...state.data, ...data };
    }
};

const actions = {
    fetch({ commit, dispatch }) {
        dispatch('loader/startLoading', 'app.user', { root: true });

        return axios.get('/api/user')
            .then(response => {
                commit('set', response.data.data);

                dispatch('loader/stopLoading', 'app.user', { root: true });
            })
            .catch(error => {
                // Vue.toasted.error('An unexpected error occured.', {
                //     duration: 3000
                // });
            });
    },

    logout({ commit }) {
        commit('set', {});

        return axios.post('/logout')
            .then(() => {
                window.location.href = '/';
            }).catch(error => {

            });
    }
};

export default {
    namespaced: true,
    state,
    getters,
    mutations,
    actions
};
