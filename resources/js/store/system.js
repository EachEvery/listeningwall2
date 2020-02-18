import { createNamespacedHelpers } from "vuex";

export default {
    ...createNamespacedHelpers("system"),

    namespaced: true,

    state: {
        initialized: true,
        adminMode: false
    },

    mutations: {
        setInitialized(state, isInitialized) {
            state.initialized = isInitialized;
        },
        setAdminMode(state, bool) {
            state.adminMode = bool;
        }
    }
};
