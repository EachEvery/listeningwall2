export default {
    computed: {
        poem({ field }) {
            return field.value;
        },
        title({ field }) {
            let title = field.value.title;

            return _.isEmpty(title) ? "Untitled" : title;
        },
        author({ field }) {
            return field.value.author;
        },
        rows({
            field: {
                value: { words }
            }
        }) {
            let rows = [...Array(16).keys()].map(n => n + 1);

            return rows.map(r => {
                return words.filter(w => w.row === `row-${r}`);
            });
        }
    }
};
