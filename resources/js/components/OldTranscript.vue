

<template>
  <div
    ref="container"
    class="absolute pin-r pin-b bg-green-lightest flex justify-end p-8 h-full overflow-auto"
    style="width: 58rem; min-height: "
    :class="{'mr-8': !hasTimestamps }"
  >
    <div>
      <div
        class="flex mb-10"
        v-for="(section, sectionIndex) in transcriptSections"
        :style="{'--baseOpacity': getSectionBaseOpacity(section, sectionIndex)}"
        :key="sectionIndex"
      >
        <caps
          class="text-category-color text-xs mr-10 self-start mt-1"
          v-if="section.time_for_humans"
        >{{section.time_for_humans}}</caps>

        <div class="max-w-xs text-lg leading-normal self-start whitespace-pre-wrap">
          <button
            class="transition border-b-4 tracking-tight focus:outline-none"
            :style="getWordStyle(sectionIndex,wordIndex)"
            :class="getWordClass(sectionIndex,wordIndex)"
            @click.prevent="() => handleClick(sectionIndex,wordIndex,  word)"
            style="margin-right: .4rem;"
            v-for="(word, wordIndex) in section.words"
            :key="wordIndex"
          >{{word}}</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import baseButton from "../components/BaseButton";
import caps from "../components/Caps";
import builder from "../store/builder.js";

export default {
  props: {
    source: {
      type: Object,
      required: true
    }
  },
  components: {
    caps,
    baseButton
  },
  data() {
    return {
      state: "default"
    };
  },
  methods: {
    ...builder.mapMutations(["toggleWord"]),
    refreshState() {
      this.state = this.words.length === 0 ? "default" : "active";
    },
    handleClick(sectionIndex, wordIndex, word) {
      this.toggleWord({
        id: this.getWordId(sectionIndex, wordIndex),
        word: word,
        source: this.source,
        row: "my-words",
        left: 0,
        wordIsHelper: false
      });
    },

    getSectionBaseOpacity(section, sectionIndex) {
      let wordsInSection = this.words.filter(word => {
        return +word.id.split(".")[1] === sectionIndex;
      });

      return wordsInSection.length > 0 ? 0.3 : 1;
    },
    getWordId(sectionIndex, wordIndex) {
      return `${this.source.id}.${sectionIndex}.${wordIndex}`;
    },
    isWordInBuilder(id) {
      return this.words.find(item => item.id === id) !== undefined;
    },
    getWordStyle(sectionIndex, wordIndex) {
      let isSelected = this.isWordInBuilder(
        this.getWordId(sectionIndex, wordIndex)
      );

      console.log("isSelected", isSelected);

      return {
        opacity: isSelected ? "1" : "var(--baseOpacity)"
      };
    },
    getWordClass(sectionIndex, wordIndex) {
      let isSelected = this.isWordInBuilder(
        this.getWordId(sectionIndex, wordIndex)
      );

      return {
        active: isSelected,
        "border-category-color": isSelected,
        "border-transparent": !isSelected,
        "text-category-color": isSelected
      };
    }
  },
  watch: {
    words: function() {
      clearTimeout(this.wordsTimeout);

      this.wordsTimeout = setTimeout(() => {
        this.refreshState();
      }, 300);
    }
  },
  computed: {
    ...builder.mapGetters(["sourceWords"]),
    height() {
      return this.$refs.container.clientHeight;
    },
    words({ sourceWords }) {
      return sourceWords.filter(item => item.source.id === this.source.id);
    },
    timestamps({ source, hasTimestamps }) {
      return hasTimestamps
        ? source.video_timestamps.split(",").map(item => item.trim())
        : [];
    },
    hasTimestamps({ source }) {
      return source.video_timestamps !== null;
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
    baseOpacity({ state }) {
      return state === "active" ? 0.3 : 1;
    }
  },
  mounted() {
    this.$nextTick(() => {
      this.refreshState();
    });
  }
};
</script>
<style scoped>
button:not(.active) {
  border-color: transparent;
}

button {
  opacity: var(--baseOpacity);
}
</style>