<template>
  <div :style="{ transform: `scale(${scale})` }" style="transform-origin: top" id="community-card">
    <div
      class="border-b border-dotted transition"
      :class="{
                'border-transparent': showingWordInfo,
                'border-grey-lightest': !showingWordInfo
            }"
    >
      <div
        v-for="index in 16"
        :key="index"
        class="h-10 border-t border-dotted row relative transition"
        :class="{
                    'border-transparent': showingWordInfo,
                    'border-grey-lightest': !showingWordInfo
                }"
        :id="`row-${index}`"
      >
        <word
          @click="e => showPopoverFor(e, word)"
          v-for="word in getRowWords(`row-${index}`)"
          :id="word.id"
          :style="{
                        left: `${word.left_percentage}%`,
                        top: `0`,
                        'background-color': word.color
                    }"
          :key="word.id"
          :word="word"
          :is-active="popoverWord && popoverWord.id === word.id"
          class="no-clone z-100 absolute transition-slow"
          :class="{
                        'opacity-0':
                            fadeInWordInfo && word.id !== popoverWord.id
                    }"
          :data-timestamp="word.timestamp"
          :data-word-id="word.id"
        />
      </div>
    </div>

    <word-info-popover
      id="popover"
      v-if="showingWordInfo"
      :style="popoverStyle"
      :word="popoverWord"
      @close="closePopover"
    />
  </div>
</template>
<script>
import word from "./Word";
import calculateLeft from "../mixins/calculateLeft";
import sources from "../store/sources";
import randomString from "../functions/randomString";
import wordInfoPopover from "../components/WordInfoPopover";
import $ from "jquery";

export default {
  components: {
    word,
    wordInfoPopover
  },

  mixins: [calculateLeft],

  props: {
    words: {
      type: Array,
      required: true
    },
    scale: {
      default: 0.9
    }
  },

  data() {
    return {
      state: "default",
      popoverWord: undefined
    };
  },

  computed: {
    ...sources.mapState(["sources"]),
    wordsWithSources() {
      let source = this.sources.find(s => +s.id === +word.sourceId);

      return this.words.map(w => ({
        ...w,
        id: randomString(),
        source: this.sources.find(s => +s.id === +w.sourceId)
      }));
    },
    showingWordInfo({ popoverWord }) {
      return popoverWord !== undefined;
    },
    fadeInWordInfo({ state }) {
      return state === "showWordInfo";
    },

    popoverStyle({ popoverWord }) {
      let $popover = $("#popover"),
        $card = $("#community-card"),
        $word = $(`[id="${popoverWord.id}"]`);

      let top = $word.offset().top - $card.offset().top;
      let left = $card.outerWidth() / 2 - popoverWidth / 2;

      let popoverHeight = $popover.outerHeight();
      let popoverWidth = $popover.outerWidth();

      return {
        opacity: this.fadeInWordInfo ? 1 : 0,
        "--category-color": popoverWord.source.category.color,
        top: top + 25 + "px",
        left: left + "px",

        "margin-top": `calc(-${popoverHeight / 2}px)`,
        transition: "600ms opacity ease"
      };
    }
  },
  watch: {
    state() {
      this.$emit("state-change", this.state);
    }
  },
  methods: {
    closePopover() {
      if (this.state === "default") {
        return;
      }

      this.state = "default";

      setTimeout(() => {
        this.popoverWord = undefined;
      }, 600);
    },
    showPopoverFor(e, word) {
      e.preventDefault();

      if (word.source === undefined) {
        return;
      }

      if (this.showingWordInfo) {
        return;
      }

      this.popoverWord = word;

      this.$nextTick(() => {
        this.state = "showWordInfo";
      });
    },
    getColor(word) {
      try {
        let source = this.sources.find(s => +s.id === +word.sourceId);

        return source.category.color;
      } catch (e) {
        return "#000";
      }
    },
    getRowWords(row) {
      return this.wordsWithSources.filter(word => word.row === row.toString());
    }
  }
};
</script>
