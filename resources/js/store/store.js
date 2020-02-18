import Vue from "vue";
import Vuex, { Store } from "vuex";
import VuexPersist from "vuex-persist";

import builder from "./builder";
import sources from "./sources";
import responses from "./responses";
import system from "./system";

Vue.use(Vuex);

const persistance = new VuexPersist({
    storage: window.localStorage
});

let store = new Store({
    namespaced: true,
    modules: {
        builder,
        sources,
        responses,
        system
    },
    plugins: [persistance.plugin]
});

export default store;
