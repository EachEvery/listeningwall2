import { createNamespacedHelpers } from "vuex";
import { getHelperWords, getWordObj } from "../functions/helperWords";

import builderUtils from "../functions/builderUtils";
import Axios from "axios";
import randomString from "../functions/randomString";

export default {
    ...createNamespacedHelpers("builder"),

    namespaced: true,

    state: {
        words: [...getHelperWords().map(word => getWordObj(word))],
        videoUrl: undefined,
        thumbnailUrl: undefined,
        builderWidth: undefined,
        blob: undefined,
        placingWord: false,
        publishKey: randomString(),
        name: "",
        title: "",
        phone: ""
    },

    getters: {
        sourceWords({ words }) {
            return words.filter(item => !item.wordIsHelper);
        },
        wordsInCard({ words }) {
            return words
                .filter(item => item.row.includes("row"))
                .filter(i => !i.row.includes("NaN"));
        },
        commonWords({ words }) {
            return words.filter(w => w.row === "word-bank");
        },
        wordsToSubmit({}, { wordsInCard }) {
            return wordsInCard.map(item => ({
                word: item.word,
                sourceId: item.source.id,
                left: item.left,
                row: item.row
            }));
        }
    },

    mutations: {
        setPhone(state, phone) {
            state.phone = phone;
        },
        setBlob(state, blob) {
            state.blob = blob;
        },
        replaceWord(state, newWordObj) {
            state.words = [
                ...state.words.filter(w => w.id !== newWordObj.id),
                newWordObj
            ];
        },
        setPlacingWord(state, bool) {
            state.placingWord = bool;
        },
        setName(state, name) {
            state.name = name;
        },
        setTitle(state, title) {
            state.title = title;
        },
        setVideoUrl(state, url) {
            state.videoUrl = url;
        },
        setThumbnailUrl(state, url) {
            state.thumbnailUrl = url;
        },
        setBuilderWidth(state, width) {
            console.log(state.builderWidth, "width");
            state.builderWidth = width;
        },
        resetBuilder(state) {
            state.words = [...getHelperWords().map(word => getWordObj(word))];
            state.publishKey = randomString();
            state.videoUrl = undefined;
            state.thumbnailUrl = undefined;
            state.name = "";
            state.title = "";
            state.phone = "";
            state.blob = undefined;
        },
        setWords(state, words) {
            state.words = words;
        },
        deleteWord(state, word) {
            let index = state.words.findIndex(item => item.id === word.id);

            state.words.splice(index, 1);
        },

        setWordPosition(state, { wordId, row, left, updated }) {
            let index = state.words.findIndex(item => item.id === wordId);

            // If it's coming from the word bank,
            // we're going to clone it instead of moving it.
            let item =
                state.words[index].row === "word-bank"
                    ? getWordObj(state.words[index].word)
                    : state.words.splice(index, 1)[0];

            item.row = row;
            item.left = left;
            item.updated = updated;

            state.words.push(item);
        },

        addWord(state, wordObject) {
            state.words.push(wordObject);
        },

        toggleWord(state, wordObject) {
            let index = state.words.findIndex(
                item => item.id === wordObject.id
            );

            if (index === -1) {
                state.words.push(wordObject);
            } else {
                state.words.splice(index, 1);
            }
        }
    },

    actions: {
        addWordToBuilder(
            { rootState, commit, getters: { wordsInCard } },
            word
        ) {
            let lastWord =
                wordsInCard.length === 0
                    ? undefined
                    : wordsInCard[wordsInCard.length - 1];

            let newPos = builderUtils.getNewWordPosition(lastWord, true);
            let source = rootState.sources.sources.find(
                s => +s.id === word.sourceId
            );

            commit("addWord", {
                ...word,
                ...newPos,
                wordIsHelper: false,
                source
            });
        },
        refreshHelperWords({ commit, state }) {
            let nonWordBankWords = state.words.filter(
                item => item.row !== "word-bank"
            );

            commit("setWords", nonWordBankWords);

            [...getHelperWords().map(word => getWordObj(word))].forEach(
                word => {
                    commit("addWord", word);
                }
            );
        },

        copyToBuilder({ rootState, getters, commit, state }, response) {
            let sources = rootState.sources.sources;

            getters.wordsInCard.forEach(item => {
                commit("deleteWord", item);
            });

            response.words.forEach(word => {
                let source = sources.find(s => +s.id === word.sourceId);

                debugger;

                let calculatedLeftFromPerc =
                    (word.left_percentage / 100) * state.builderWidth;

                commit("addWord", {
                    id: randomString(),
                    word: word.word,
                    source:
                        source === undefined
                            ? {
                                  category: {
                                      color: "#484848"
                                  }
                              }
                            : source,
                    row: word.row,
                    left: calculatedLeftFromPerc,
                    wordIsHelper: source === undefined,
                    animated: true
                });
            });
        },

        removeWordBankWords({ state, commit }) {
            // this leaves the words on the card
            // but removes theh ones from the words bank
            state.words.forEach(item => {
                if (item.row === "my-words") {
                    commit("deleteWord", item);
                }
            });
        },
        submitResponse({ dispatch, commit, getters: { wordsToSubmit } }) {
            return new Promise(async resolve => {
                let { data: response } = await Axios.post(
                    window.location.pathname + "/responses",
                    {
                        response: {
                            words: wordsToSubmit
                        }
                    }
                );

                // dispatch("removeWordBankWords");

                setTimeout(() => {
                    commit("resetBuilder");
                }, 2000);

                resolve(response);
            });
        }
    }
};
