import multiply from "./multiply";

export default {
    getNewWordPosition(word, forceHighRows = false) {
        if (word === undefined) {
            return {
                row: `row-3`,
                left: 200
            };
        }

        let oldRow = +word.row.replace("row-", "");
        let newRow = oldRow === 16 ? 15 : oldRow + 1;

        let newLeft = word.left + multiply(10);

        if (newRow > 12) {
            newRow = 1;
        }

        return {
            row: `row-${newRow}`,
            left: newLeft > 200 ? 0 : newLeft
        };
    }
};
