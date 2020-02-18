require("./bootstrap");

import FullPageResponse from "./FullPageResponse";
import Vue from "vue";

new Vue({
    el: "#app",
    render: h => h(FullPageResponse)
});
