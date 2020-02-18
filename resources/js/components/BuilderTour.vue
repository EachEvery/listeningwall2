<template>
    <div>
        <base-button
            @click="handleClick"
            v-html="svg('help', 'w-10 h-10 p-1 text-green')"
        ></base-button>

        <portal to="modals">
            <v-tour name="builder" ref="tour" :steps="steps"></v-tour>
        </portal>
    </div>
</template>
<script>
import baseButton from "./BaseButton";

export default {
    components: {
        baseButton
    },
    methods: {
        handleClick() {
            this.$tours["builder"].start();
        }
    },
    data() {
        return {
            steps: [
                {
                    target: "#card", // We're using document.querySelector() under the hood
                    content: `<h1>Move Words</h1><p>Rearrange words by dragging them.</p>!`
                },
                {
                    target: "#common-words-button",
                    content:
                        "<h1>Add Words</h1><p>Tap this button to add common words to your poem.</p>!"
                },
                {
                    target: "#drop-targets",
                    content:
                        "<h1>Duplicate, Delete, View Source</h1><p>Drag words to each area to duplicate, delete, or view it.</p>!",
                    params: {
                        placement: "top"
                    }
                }
            ]
        };
    }
};
</script>
