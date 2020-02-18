/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require("./bootstrap");

require("webpack-jquery-ui");
require("webpack-jquery-ui/css");
require("./functions/touchPunch");

import Vue from "vue";
import App from "./App.vue";
import store from "./store/store";
import router from "./router";

import IScrollView from "vue-iscroll-view";
import IScroll from "iscroll";
import PortalVue from "portal-vue";
import vClickOutside from "v-click-outside";

Vue.prototype.svg = require("./svg");

require("vue-tour/dist/vue-tour.css");

Vue.use(PortalVue);
Vue.use(IScrollView, IScroll);
Vue.use(vClickOutside);

Object.defineProperty(HTMLMediaElement.prototype, "playing", {
    get: function() {
        return !!(
            this.currentTime > 0 &&
            !this.paused &&
            !this.ended &&
            this.readyState > 2
        );
    }
});

new Vue({
    el: "#app",
    store,
    router,
    render: h => h(App)
});
