import randomString from "./randomString";

export const helperWords = [
    "a",
    "an",
    "or",
    "the",
    "but",
    "so",
    "awhile",
    "around",
    "against",
    "beneath",
    "before",
    "behind",
    "between",
    "below",
    "above"
];

export const getHelperWords = () => {
    if (window.collection.helper_words) {
        return window.collection.helper_words;
    }

    return helperWords;
};

export const getWordObj = word => {
    return {
        id: `helper.${word}.${randomString()}`,
        word: word,
        source: {
            category: {
                color: "#484848"
            }
        },
        row: "word-bank",
        left: 0,
        wordIsHelper: true,
        animated: word.animated
    };
};
