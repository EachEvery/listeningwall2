<template>
  <div
    @click="handleClick"
    class="px-4 py-2 bg-category-color text-white rounded-full inline-block text-lg uppercase font-sans font-black border-2 border-white"
    :class="{ 'transition-slow': enableTransition }"
    :style="wordStyle"
    ref="word"
  >{{ word.word }}</div>
</template>

<style scoped>
.bounceIn {
  animation-name: bounceIn;
  animation-duration: 500ms;
}

div {
  transform-origin: top left;
}

@keyframes bounceIn {
  0% {
    transform: scale(1);
  }

  50% {
    transform: scale(1.08) translateY(-0.2rem);
  }
}
</style>

<script>
import caps from "./Caps";
import { mapMutations } from "vuex";
import builder from "../store/builder";
export default {
  mounted() {
    this.$nextTick(() => {
      this.bounds = this.$refs.word.getBoundingClientRect();

      if (!this.word.animated && !this.disableAnimation) {
        this.animate();
      }
    });
  },
  components: {
    caps
  },
  props: {
    disableAnimation: {
      default: true
    },
    word: {
      type: Object,
      required: true
    },
    bounceIn: {
      default: true
    },
    isActive: Boolean
  },
  data() {
    return {
      state: "default",
      bounds: undefined,
      enableTransition: !this.word.animated
    };
  },
  methods: {
    ...builder.mapMutations(["replaceWord"]),
    animate() {
      setTimeout(() => {
        this.replaceWord({
          ...this.word,
          animated: true
        });
      }, 400);
    },
    handleClick(e) {
      this.$emit("click", e);
    },
    handleDblClick(e) {
      this.$emit("dblclick", e);
    }
  },
  watch: {
    word: {
      deep: true,
      handler() {
        setTimeout(() => {
          this.enableTransition = false;
        }, 800);
      }
    }
  },
  computed: {
    wordStyle({ word, bounds, category, isActive }) {
      if (isActive) {
        return {
          background: "rgba(0,0,0,.05)",
          color: "rgba(0,0,0,.8)"
        };
      }

      if (bounds === undefined) {
        return { backgroundColor: category.color };
      }

      if (this.disableAnimation) {
        return { backgroundColor: category.color };
      }

      if (this.bounds === undefined) {
        return {};
      }

      if (!this.word.animated) {
        let originBounds = this.word.originBounds;
        let x = originBounds.x - this.bounds.x;
        let y = originBounds.y - this.bounds.y;
        let transformValue = `translateY(${y}px) translateX(${x}px) scale(.5)`;

        return {
          transform: transformValue,
          backgroundColor: category.color,
          transition: "none",
          visiblity: "hidden"
        };
      } else {
        return { backgroundColor: category.color };
      }
    },
    shouldBounceIn({ state, bounceIn }) {
      return state === "bounceIn" && bounceIn;
    },
    category({ word }) {
      try {
        return word.source.category;
      } catch {
        return {
          color: "rgba(27, 27, 27)"
        };
      }
    }
  }
};
</script>
