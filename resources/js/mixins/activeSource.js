import sources from "../store/sources";

export default {
    computed: {
        ...sources.mapGetters(["withResponses"]),

        source({ withResponses }) {
            return withResponses.find(
                item => item.id === +this.$route.params.sourceId
            );
        },

        category({ source }) {
            return source.category;
        },

        hasShortDescription({ source }) {
            return source.text.length < 470;
        },

        hasShortTitle({ source }) {
            return source.title.length < 30;
        },

        hasSuperLongTitle({ source }) {
            return source.title.length > 65;
        }
    }
};
