import get from "../functions/get";

import { createNamespacedHelpers } from "vuex";
export const SET_SOURCES = "SET_SOURCES";

import _ from "lodash";

export default {
    ...createNamespacedHelpers("sources"),
    namespaced: true,
    state: {
        sources: []
    },
    getters: {
        isAdmin() {
            return window.isAdmin;
        },
        rows({ sources }) {
            return Object.values(_.groupBy(sources, s => s.pivot.row));
        },
        responses({}, {}, rootState) {
            return rootState.responses.responses;
        },
        withResponses({ sources }, { responses }) {
            return sources.map(source => ({
                ...source,
                responses: responses.reverse().filter(response => {
                    return (
                        response.words.filter(
                            word => word.sourceId === source.id
                        ).length > 0
                    );
                })
            }));
        }
    },
    mutations: {
        [SET_SOURCES](state, sources) {
            state.sources = sources;
        },

        replaceSources({ sources }, newSources) {
            sources.splice(0, sources.length, ...newSources);
        },

        updateSource({ sources }, source) {
            let index = sources.findIndex(s => s.id === source.id);

            sources.splice(index, 1, source);
        }
    },
    actions: {
        normalizeRowOrder({ getters: { rows }, commit }) {
            rows.forEach(sources => {
                sources.forEach((source, i) => {
                    commit("updateSource", {
                        ...source,
                        pivot: {
                            ...source.pivot,
                            order: i + 1
                        }
                    });
                });
            });
        },
        loadSources(context) {
            return new Promise(async resolve => {
                let { data } = await get(
                    `${window.location.pathname}/sources?locale=${window.locale}`
                );
                context.commit(SET_SOURCES, data.data);

                resolve(data.data);
            });
        },
        moveDown({ getters: { rows }, commit, dispatch }, source) {
            let newRow = source.pivot.row + 1 > 4 ? 1 : source.pivot.row + 1;

            commit("updateSource", {
                ...source,

                pivot: {
                    ...source.pivot,
                    row: newRow
                }
            });

            dispatch("normalizeRowOrder");
        },

        moveUp({ getters: { rows }, commit, dispatch }, source) {
            let newRow = source.pivot.row - 1 === 0 ? 4 : source.pivot.row - 1;

            commit("updateSource", {
                ...source,

                pivot: {
                    ...source.pivot,
                    row: newRow
                }
            });

            dispatch("normalizeRowOrder");
        },
        moveLeft({ state: { sources }, commit }, source) {
            let rowSources = sources.filter(
                s => s.pivot.row === source.pivot.row
            );

            let prevOrder =
                source.pivot.order - 1 === 0
                    ? rowSources.length
                    : source.pivot.order - 1;

            let swapOrderIndex = rowSources.findIndex(
                s => s.pivot.order === prevOrder
            );

            commit("updateSource", {
                ...rowSources[swapOrderIndex],
                pivot: {
                    ...rowSources[swapOrderIndex].pivot,
                    order: source.pivot.order
                }
            });

            commit("updateSource", {
                ...source,
                pivot: {
                    ...source.pivot,
                    order: prevOrder
                }
            });
        },
        moveRight({ state: { sources }, commit }, source) {
            let rowSources = sources.filter(
                s => s.pivot.row === source.pivot.row
            );

            let nextOrder =
                source.pivot.order + 1 > rowSources.length
                    ? 1
                    : source.pivot.order + 1;

            let swapOrderIndex = rowSources.findIndex(
                s => s.pivot.order === nextOrder
            );

            commit("updateSource", {
                ...rowSources[swapOrderIndex],
                pivot: {
                    ...rowSources[swapOrderIndex].pivot,
                    order: source.pivot.order
                }
            });

            commit("updateSource", {
                ...source,
                pivot: {
                    ...source.pivot,
                    order: nextOrder
                }
            });
        }
    }
};
