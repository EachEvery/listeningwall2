import get from "../functions/get";
import { createNamespacedHelpers } from "vuex";
import randomString from "../functions/randomString";

const SET_RESPONSES = "SET_RESPONSES";

export default {
    ...createNamespacedHelpers("responses"),
    namespaced: true,
    state: {
        responses: []
    },
    getters: {},
    mutations: {
        addResponse({ responses }, response) {
            responses.push(response);
        },
        [SET_RESPONSES](state, responses) {
            state.responses = responses;
        }
    },
    actions: {
        loadResponses(context) {
            return new Promise(async resolve => {
                let { data } = await get(
                    window.location.pathname + "/responses"
                );
                context.commit(SET_RESPONSES, data);
                resolve(data);
            });
        }
    }
};
