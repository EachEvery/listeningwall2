import Vue from "vue";
import VueRouter from "vue-router";

Vue.use(VueRouter);

export default new VueRouter({
    routes: [
        {
            path: "/sources/:sourceId",
            name: "source"
        },
        {
            path: "/sources/:sourceId/responses",
            name: "sourceResponses"
        },
        {
            path: "/responses/:responseId"
        }
    ]
});
