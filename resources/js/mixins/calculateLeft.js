export default {
    props: {
        publishedHeight: {
            type: Number,
            default: 2600 // resolution of 4k screens
        }
    },

    data() {
        return {
            currentHeight: window.innerHeight
        };
    },

    methods: {
        calculateLeftPerc(left) {
            let remBase = (this.publishedHeight / 100) * 0.833;
            let builderRem = 29;
            let originalBuilderPx = builderRem * remBase;

            return (left / originalBuilderPx) * 100;
        }
    },
    computed: {
        multiplier({ currentHeight, publishedHeight }) {
            return currentHeight / publishedHeight;
        }
    },
    mounted() {
        console.log(this.publishedHeight);

        window.addEventListener("resize", () => {
            this.currentHeight = window.innerHeight;
        });
    }
};
