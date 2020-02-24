<template>
    <div :style="{ width: `${calc(8.75)}rem`, height: `${calc(9.06)}rem` }">
        <div style="transform-origin: top">
            <div
                class="border-b border-dotted border-grey-lightest"
                :style="{ width: `${calc(8.6515)}rem` }"
            >
                <div
                    v-for="index in 16"
                    :key="index"
                    class="border-t border-dotted border-grey-lightest row relative"
                    :style="{
                        height: `${calc(0.65)}rem`,
                        'line-height': '.73rem'
                    }"
                    :id="`row-${index}`"
                >
                    <div
                        v-for="(word, i) in getRowWords(`row-${index}`)"
                        :key="i"
                        class="text-white rounded-full inline-block text-lg uppercase font-black border-white absolute"
                        :style="getWordStyle(word)"
                    >
                        {{ word.word }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import word from "./Word";
import calcLeft from "../mixins/calculateLeft";

export default {
    components: {
        word
    },
    mixins: [calcLeft],
    props: {
        words: {
            type: Array,
            required: true
        }
    },
    methods: {
        calc(num) {
            return num * 1.6;
        },
        getWordStyle(word) {
            return {
                left: word.left_percentage,
                // marginTop: `${this.calc(0.4)}rem`,
                fontSize: `${this.calc(0.3)}rem`,
                borderWidth: `${this.calc(0.016)}rem`,
                background: this.getColor(word),
                padding: `${this.calc(0.023)}rem ${this.calc(0.25)}rem`
            };
        },
        getColor(word) {
            try {
                return word.source.category.color;
            } catch {
                return "rgba(27, 27, 27)";
            }
        },

        getRowWords(row) {
            return this.words.filter(word => word.row === row.toString());
        }
    }
};
</script>
