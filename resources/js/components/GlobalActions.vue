<template>
    <div
        class="border-t bg-black justify-center items-center pt-5 pb-3 text-center"
        style="border-color: rgba(255, 255, 255, .2); width: 100vh; transform: rotate(90deg) translateY(-100%); transform-origin: 0 0;"
    >
        <sidebar-button
            label="Stories"
            :class="{ 'opacity-25': page === 'stories' }"
            @click="$emit('page-link-clicked', 'stories')"
        />

        <sidebar-button
            label="Community"
            @click="$emit('page-link-clicked', 'community')"
            :class="{ 'opacity-25': page === 'community' }"
        />

        <sidebar-button
            label="My Poem"
            @click="$emit('poem-clicked')"
            :class="{ 'opacity-25': !poemsEnabled }"
            style="margin-right: 5rem"
        />

        <a
            :class="{ 'opacity-25': locale === language.code }"
            class="font-bold mx-2 no-underline bg-white rounded-full w-8 h-8 text-black inline-block inline-flex items-center justify-center -mt-2"
            style="transform: rotate(-90deg) translateX(.5rem)"
            v-for="language in collection.languages"
            :key="language.id"
            :href="`?locale=${language.code}`"
        >
            <span class="text-xs">{{ language.code }}</span>
        </a>
    </div>
</template>

<style lang="scss">
.animateSourceWord {
    animation-name: sourceWord;
    animation-duration: 700ms;
}

@keyframes sourceWord {
    50% {
        background: var(--category-color);
        transform: scale(1.3) translateY(-0.08rem);
        color: rgba(255, 255, 255, 0.8);
    }
}
</style>

<script>
import sidebarButton from "./SidebarButton";
import collectionIcon from "./CollectionIcon";
import backIcon from "./BackIcon";
import gridIcon from "./GridIcon";
import messageIcon from "./MessageIcon";
import builder from "../store/builder";
import axios from "axios";

export default {
    props: {
        poemsEnabled: Boolean,
        page: String
    },
    data() {
        return {
            state: "default"
        };
    },
    methods: {
        getClass(path) {
            let active = this.$route.path === path;

            return {
                "opacity-100": !active,
                "opacity-25": active
            };
        },
        navigate(path) {
            this.$router.push(path);
        },
        goBack() {
            this.$router.go(-1);
        },
        emphesizeCount() {
            this.state = "newCount";
            setTimeout(() => {
                this.state = "default";
            }, 701);
        }
    },
    watch: {
        sourceWords: function() {
            if (this.$route.path === "/builder") {
                return;
            }
            this.emphesizeCount();
        }
    },
    computed: {
        ...builder.mapGetters(["sourceWords"]),
        isSource({ $route }) {
            return $route.name === "source";
        },
        latestWordColor({ latestWord }) {
            if (latestWord === undefined) {
                return "#fff";
            } else {
                return latestWord.source.category.color;
            }
        },
        latestWord({ sourceWords }) {
            return [...sourceWords].pop();
        },

        newCount({ state }) {
            return state === "newCount";
        },

        showSidebarButton() {
            return this.$route.path !== "/";
        },

        getSourceWordClass({ newCount }) {
            return {
                animateSourceWord: newCount
            };
        },

        collection() {
            return window.collection;
        },

        locale() {
            return window.locale;
        }
    },
    components: {
        sidebarButton,
        collectionIcon,
        gridIcon,
        messageIcon,
        backIcon
    }
};
</script>
