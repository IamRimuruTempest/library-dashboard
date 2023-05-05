import Vue from "vue";
import VueRouter from "vue-router";
Vue.use(VueRouter);
import Dashboard from "./pages/Dashboard";
import Books from "./pages/Books";
import BooksBorrowed from "./pages/BooksBorrowed";
import Users from "./pages/Users";
import Events from "./pages/Events";
import Account from "./pages/Account";
export default new VueRouter({
    mode: "history",
    routes: [
        {
            path: "/",
            name: "dashboard",
            component: Dashboard,
        },
        {
            path: "/dashboard",
            name: "home",
            component: Dashboard,
        },
        {
            path: "/books",
            name: "books",
            component: Books,
        },
        {
            path: "/books-borrowed",
            name: "books-borrowed",
            component: BooksBorrowed,
        },
        {
            path: "/events",
            name: "events",
            component: Events,
        },
        {
            path: "/users",
            name: "users",
            component: Users,
        },
        {
            path: "/account",
            name: "account",
            component: Account,
        },
    ],
});
