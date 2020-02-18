export default string => {
    let language = window.collection.languages.find(
        l => l.code === window.locale
    );

    if (language === undefined || language.tokens === null) {
        return string;
    }

    let tokens = JSON.parse(language.tokens);

    try {
        return tokens[string] === undefined ? string : tokens[string];
    } catch {
        console.log("missing translation", language.code, string);
        return string;
    }
};
