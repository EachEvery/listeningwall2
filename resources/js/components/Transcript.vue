<template>
  <div
    class="absolute pin-y pin-x flex justify-end transition-slow"
    :class="{'overflow-auto': open}"
    :style="containerStyle"
  >
    <div
      class="w-full flex justify-end pr-10 relative transition-slow"
      :style="{'background-color': category.color_light, transform: open ? 'translateY(24rem)' :'none'}"
      style="width: 59.5rem; min-height: 40rem"
    >
      <caps
        class="text-black self-center text-base w-full text-left absolute pin-x pin-t pb-4"
        style="transform: rotate(90deg) translateY(-100%) translateX(10.5rem); transform-origin: 0 0;"
      >Transcript</caps>

      <div class="pt-4 text-black">
        <base-button @click="toggleOpen">
          <h1
            class="font-black leading-tight pb-4 pr-10 mb-12 block text-left"
            style="font-size: 3.5rem; line-height: 3.5rem; border-bottom: .6rem solid #000"
          >
            Select Your
            <br />Words ↓
          </h1>
        </base-button>
        <div
          class="flex mb-10"
          v-for="(section, sectionIndex) in transcriptSections"
          :key="sectionIndex"
        >
          <caps
            class="text-category-color text-xs mr-10 self-start mt-1"
            v-if="section.time_for_humans"
          >{{section.time_for_humans}}</caps>

          <div class="max-w-xs text-lg leading-normal self-start whitespace-pre-wrap">
            <button
              class="transition tracking-tight focus:outline-none"
              :ref="`word.${sectionIndex}.${wordIndex}`"
              @click.prevent="() => handleClick(sectionIndex,wordIndex,  word)"
              style="margin-right: .4rem;"
              v-for="(word, wordIndex) in section.words"
              :key="wordIndex"
            >{{word}}</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import builderModule from "../store/builder";
import sourcesModule from "../store/sources";
import caps from "./Caps";
import baseButton from "./BaseButton";
import randomString from "../functions/randomString";

export default {
  components: {
    caps,
    baseButton
  },
  props: {
    open: Boolean
  },

  data() {
    return {
      state: "default",

      /**
       * Words that are currently
       * being animated from the transcript to the
       * builder
       */
      wordsBeingMoved: []
    };
  },

  methods: {
    ...builderModule.mapActions(["addWordToBuilder"]),
    ...builderModule.mapMutations(["setPlacingWord"]),

    toggleOpen() {
      this.$emit("toggle");
    },

    refreshState() {
      this.state = this.words.length === 0 ? "default" : "active";
    },

    getMovingIndex(wordId) {
      return this.wordsBeingMoved.findIndex(w => w === wordId);
    },

    handleClick(sectionIndex, wordIndex, word) {
      let wordId = `${sectionIndex}.${wordIndex}`;

      if (this.getMovingIndex(wordId) > -1) {
        return;
      }

      this.wordsBeingMoved.push(wordId);

      setTimeout(
        () => {
          let element = this.$refs[`word.${sectionIndex}.${wordIndex}`][0];
          let bounds = element.getBoundingClientRect();

          this.addWordToBuilder({
            word,
            id: randomString(),
            originBounds: bounds,
            animated: false,
            sourceId: this.source.id
          });

          this.wordsBeingMoved.splice(this.getMovingIndex(wordId), 1);
        },
        this.placingWord ? 0 : 300
      );

      this.setPlacingWord(true);

      clearTimeout(this.closeTimeout);

      this.closeTimeout = setTimeout(() => {
        this.setPlacingWord(false);
      }, 1500);
    },

    getSectionBaseOpacity(section, sectionIndex) {
      let wordsInSection = this.words.filter(word => {
        return +word.id.split(".")[1] === sectionIndex;
      });

      return wordsInSection.length > 0 ? 0.3 : 1;
    },
    getWordId(sectionIndex, wordIndex) {
      return `${this.source.id}.${sectionIndex}.${wordIndex}`;
    }
  },
  watch: {
    wordsBeingMoved(val) {
      console.log("words being moved", val);
    },
    words: function() {
      clearTimeout(this.wordsTimeout);

      this.wordsTimeout = setTimeout(() => {
        this.refreshState();
      }, 300);
    }
  },
  computed: {
    ...sourcesModule.mapGetters(["withResponses"]),
    ...builderModule.mapGetters(["sourceWords"]),
    ...builderModule.mapState(["placingWord"]),
    containerStyle({ open }) {
      return { transform: `translateY(${open ? "0" : "88rem"})` };
    },
    words({ sourceWords, source }) {
      return sourceWords.filter(item => item.source.id === source.id);
    },
    transcriptSections({ source, timestamps, hasTimestamps }) {
      let lines = source.transcript
        .split("\n\n")
        .filter(item => item.trim() !== "");

      return lines.map((text, i) => {
        let words = text.split(/\s/g);

        return {
          time_for_humans: hasTimestamps ? timestamps[i] : undefined,
          words
        };
      });
    },
    source({ withResponses }) {
      return withResponses.find(
        item => item.id === +this.$route.params.sourceId
      );
    },
    category({ source }) {
      return source.category;
    },
    baseOpacity({ state }) {
      return state === "active" ? 0.3 : 1;
    },
    height() {
      return this.$refs.container.clientHeight;
    }
  }
};
</script>