import Vue from 'vue';
import Vuex from 'vuex';
import createPersistedState from 'vuex-persistedstate';

Vue.use(Vuex);

const store = new Vuex.Store({
    state: {
        userApiToken: '',
        permissions: [],
    },
    mutations: {
        setLogin(state, response) {
            state.userApiToken = response.token;
        },
        resetState(state) {
            state.userApiToken = '';
        },
        setPermission(state, items) {
            state.permissions = Object.values(items).map(item => {
                return item.name;
            })
        }
    },
    plugins: [createPersistedState()],
});

export default store;
