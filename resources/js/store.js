import axios from "axios";
import Vue from "vue";
import Vuex from "vuex";
import createPersistedState from "vuex-persistedstate";

Vue.use(Vuex);
const persistedData = new createPersistedState({
    key: "user",
    storage: localStorage,
    reducer: (state) => ({
        loggedInUser: state.loggedInUser,
    }),
});

export default new Vuex.Store({
    state: {
        loggedInUser: null,

        // allProgrammers: [],
        allBooks: [],
        allAccounts: [],
    },

    actions: {
        storeUser({ commit }, data) {
            commit("storeUser", data);
        },

        login(context) {
            let user_data = {
                id: 1,
                name: "dos",
            };

            axios.post("api/getUser", user_data).then((response) => {
                context.commit("login", response.data);
            });
        },

        logout(context) {
            context.commit("logout");
        },

        // getProgrammers(context) {
        //     axios({
        //         method: "post",
        //         url: "api/programmers",
        //     }).then((res) => {
        //         context.commit("getProgrammers", res.data);
        //     });
        // },

        getBooks(context) {
            axios({
                method: "post",
                url: "/api/books",
            }).then((res) => {
                context.commit("getBooks", res.data);
            });
        },

        getAccounts(context) {
            axios({
                method: "post",
                url: "/api/accounts",
            }).then((res) => {
                context.commit("getAccounts", res.data);
            });
        },
    },

    mutations: {
        storeUser: (state, data) => {
            state.loggedInUser = data;
        },

        login(state, data) {
            state.loggedInUser = data;
            state.isLoggedin = true;
        },

        logout(state) {
            state.loggedInUser = {};
            state.isLoggedin = false;
        },

        // getProgrammers(state, data) {
        //     state.allProgrammers = data;
        // },

        getBooks(state, data) {
            state.allBooks = data;
        },

        getAccounts(state, data) {
            state.allAccounts = data;
        },
    },
    getters: {},
    plugins: [persistedData],
});
