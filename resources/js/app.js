require("./bootstrap");
import Vue from "vue";
import Vuetify from "vuetify";
import Router from "./router";
import store from "./store";
import App from "./template/App";
import VueMask from "v-mask";
import VueSweetalert2 from "vue-sweetalert2";
import "sweetalert2/dist/sweetalert2.min.css";
import VueApexCharts from "vue-apexcharts";

Vue.use(VueApexCharts);
Vue.use(Vuetify);
Vue.use(VueMask);
Vue.use(VueSweetalert2);
Vue.component("apexchart", VueApexCharts);
// new Vue({
//     el: "#app",
//     store,
//     router: Router,
//     vuetify: new Vuetify(),
//     render: (h) => h(App),
// });
const app = new Vue({
    el: "#app",
    store,
    router: Router,
    vuetify: new Vuetify(),
    render: (h) => h(App),
});
