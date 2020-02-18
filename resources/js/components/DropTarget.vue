<template>
    <div
        ref="container"
        class="items-center px-2 rounded h-64 flex-col flex justify-center text-center transition drop-target"
        style="height: 7.5rem;"
        :style="{
            backgroundColor: dragging ? lighterColor : 'rgba(0,0,0,.05)',
            transform: dragging ? 'scale(1.03)' : 'none'
        }"
    >
        <span
            v-html="svg(icon, 'w-8 h-8 transition')"
            :style="{ color: dragging ? color : 'inherit' }"
        ></span>
        <caps
            class="text-grey-dark mt-2 leading-normal text-2xs block transition"
            :style="{ color: dragging ? color : 'inherit' }"
        >
            <slot></slot>
        </caps>
    </div>
</template>
<style scoped lang="scss">
.drop-target.ui-droppable-hover {
    opacity: 0.25;
}
</style>
<script>
import caps from "./Caps";
import Color from "color";
import $ from "jquery";

import { droppable } from "../functions/jQueryUiHelpers";

export default {
    components: {
        caps
    },
    props: {
        icon: String,
        dragging: Boolean,
        color: String
    },
    computed: {
        lighterColor({ color }) {
            return Color(color)
                .lighten(0.9)
                .hex();
        }
    },
    methods: {
        handleDrop(e, ui) {
            this.$emit("drop", e, ui);
        }
    },
    mounted() {
        droppable(this.$refs.container, {
            drop: this.handleDrop
        });
    }
};
</script>
