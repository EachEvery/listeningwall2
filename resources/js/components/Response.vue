<template>
  <button
    style="width:16.535rem; height: 21.304rem; backface-visibility: hidden"
    class="relative focus:outline-none transition-slow bg-white"
    @click.prevent="emitClick"
  >
    <div
      class="flex flex-col relative h-full w-full pin overflow-hidden transition border py-4 items-center"
      v-click-outside="handleClickOutside"
    >
      <div
        class="text-black text-center text-xs p-0 font-black uppercase leading-none mb-2"
      >{{ response.title }}</div>

      <div class="text-black text-2xs font-black mb-4">by {{ response.author }}</div>

      <poem-card :words="[...response.words_with_color]" />
    </div>
  </button>
</template>
<style lang="scss">
.black-overlay {
  position: relative;

  &::after {
    content: "";
    position: absolute;
    top: -1rem;
    right: -1rem;
    left: -1rem;
    bottom: -1rem;
    background: #000;
    opacity: 0.9;
    z-index: 2;
    transition: 500ms all ease;
  }
}

.overlay-invisible {
  &::after {
    opacity: 0;
    visibility: hidden;
  }
}

.blue-overlay {
  position: relative;

  &::after {
    content: "";
    position: absolute;
    top: 0;
    right: 0;
    left: 0;
    bottom: 0;
    background: #4151a3;
    opacity: 0.3;
  }
}
</style>

<script>
import sources from "../store/sources.js";
import caps from "./Caps";
import poemCard from "./PoemCardSmall";

import clickable from "./BaseButton";
import __ from "../functions/translate";

export default {
  components: {
    caps,
    poemCard,
    clickable
  },
  props: {
    response: {
      type: Object,
      required: true
    }
  },
  data() {
    return {
      disabled: false,
      state: ""
    };
  },
  methods: {
    __,
    setState(state) {
      this.state = state;
    },
    handleClickOutside() {
      if (this.active && !this.fullscreen) {
        this.emitClose();
      }
    },
    copyToBuilder() {
      if (this.active) {
        this.$store.dispatch("builder/copyToBuilder", this.response);

        this.$router.push("/builder");
      }
    },
    emitClick(e) {
      if (!this.active) {
        this.$emit("click", this.response);
      }
    },
    emitClose() {
      this.$nextTick(() => {
        this.$emit("close");
      });
    }
  },
  computed: {
    ...sources.mapState({
      sources: state => state.sources
    }),
    collection() {
      return window.collection;
    },
    fullscreen({ state }) {
      return state === "fullscreen";
    },
    bigCardStyle({ fullscreen }) {
      if (!fullscreen) {
        return {
          transform: "translateY(100%) scale(.9)",
          opacity: 0
        };
      }

      return {};
    },
    controlsClass({ active }) {
      return {
        invisible: !active,
        "opacity-0": !active
      };
    },
    shouldDarken({ anyActive, active }) {
      return anyActive && !active;
    },

    controlContainerStyle({ active }) {
      return active ? {} : {};
    },

    words({ response, sources }) {
      return response.words.map(word => {
        return {
          ...word,
          source: sources.find(item => item.id === word.sourceId)
        };
      });
    }
  }
};
</script>
