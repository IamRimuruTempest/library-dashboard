import Vue from "vue";
import VueRouter from "vue-router";
Vue.use(VueRouter);
import store from "./store";
import Dashboard from "./pages/Dashboard";
import Books from "./pages/Books";
import BooksBorrowed from "./pages/BooksBorrowed";
import BooksReserved from "./pages/BooksReserved";
import BooksReturned from "./pages/BooksReturned";
import BooksArchived from "./pages/BooksArchived";
import Users from "./pages/Users";
import Events from "./pages/Events";
import Account from "./pages/Account";
import LoginPage from "./pages/LoginPage";

const routes = [
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
        path: "/books-reserved",
        name: "books-reserved",
        component: BooksReserved,
    },
    {
        path: "/books-returned",
        name: "books-returned",
        component: BooksReturned,
    },
    {
        path: "/books-archived",
        name: "books-archived",
        component: BooksArchived,
    },
    {
        path: "/school-events",
        name: "school-events",
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

    {
        path: "/login",
        name: "login",
        component: LoginPage,
    },
];

const router = new VueRouter({
    mode: "history",
    base: process.env.BASE_URL,
    routes,
});

router.beforeEach((to, from, next) => {
    if (to.path !== "/login") {
        if (store.state.loggedInUser !== null) {
            next();
        } else {
            next("/login");
        }
    } else {
        if (store.state.loggedInUser !== null) {
            next("/dashboard");
        } else {
            next();
        }
    }
});

export default router;
