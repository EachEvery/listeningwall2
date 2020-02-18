<template>
    <div
        :style="{ '--base': size }"
        class="py-4"
        style="background-color: rgb(252, 252, 252)"
    >
        <div class="poem">
            <section class="poem__info">
                <span class="poem__title">{{ title }}</span>

                <span class="poem__author">by {{ author }}</span>
            </section>

            <div class="row__container">
                <div class="row" v-for="(row, i) in rows" :key="i">
                    <div
                        v-for="(word, j) in row"
                        class="word"
                        :key="j"
                        :style="getWordStyle(word)"
                    >
                        {{ word.word }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped lang="scss">
.poem {
    padding-top: calc(var(--base, 1) * 2rem);
    padding-bottom: calc(var(--base, 1) * 5rem);

    font-family: "Work Sans";
    width: calc(var(--base, 1) * 40rem);
    display: flex;
    flex-direction: column;

    &__info {
        align-self: center;
        align-items: center;
        display: flex;
        flex-direction: column;
        justify-content: center;
        font-weight: 800;
        text-transform: uppercase;
    }

    &__title {
        font-size: calc(var(--base, 1) * 2.6rem);
    }

    &__author {
        font-size: calc(var(--base, 1) * 1.2rem);
        margin-top: calc(var(--base, 1) * 0.5rem);
    }
}

.row {
    &__container {
        /* margin-top: 3rem; */
        margin: calc(var(--base, 1) * 3rem) calc(var(--base, 1) * 3rem) 0;
        border-bottom: calc(var(--base, 1) * 1px) solid #ddd;
    }

    height: calc(var(--base, 1) * 2.5rem);
    position: relative;
    border-top: calc(var(--base, 1) * 1px) solid #ddd;
}

.word {
    position: absolute;
    border-radius: calc(var(--base, 1) * 35px);
    padding: calc(var(--base, 1) * 0.5rem) calc(var(--base, 1) * 1rem);
    color: #fff;
    text-transform: uppercase;
    font-weight: bold;
    margin-top: calc(var(--base, 1) * 0.16rem);
    font-size: calc(var(--base, 1) * 1rem);
}
</style>

<script>
import poemData from "../mixins/extractPoemDataFromNovaField";

export default {
    mixins: [poemData],
    props: {
        field: Object,
        size: {
            default: 0.3
        }
    },
    methods: {
        getWordStyle(word) {
            return {
                left: `${word.left_percentage}%`,
                backgroundColor: word.color
            };
        }
    }
};
</script>
