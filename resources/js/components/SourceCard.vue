<template>
  <base-button
    class="flex flex-col relative"
    v-bind="{ style }"
    :disabled="disableClick"
    @click.prevent="handleClick"
  >
    <caps
      class="text-category-color self-center text-xs w-full text-left absolute pin-x pin-t"
      style="transform: rotate(90deg) translateY(100%); transform-origin: 0 0;"
    >{{ category.name }}</caps>

    <div :style="thumbnailDimensions" class="relative">
      <div class="relative">
        <img
          :alt="source.title"
          :class="{ 'opacity-0': !loaded, invisible: !loaded }"
          :style="{
                        transform: loaded ? 'none' : 'translateY(.5rem)',
                        height: sizes[source.pivot.size]
                    }"
          class="object-cover w-full h-full relative -move-xy-2 transition"
          :src="source.thumbnail"
          @load="handleImgLoad"
        />

        <div class="absolute pin flex justify-center items-center">
          <play-icon :style="{color: category.color}" class="w-12 h-12 shadow-lg rounded-full" />
        </div>

        <transition-group
          v-if="showAdminControls"
          tag="div"
          name="list-complete"
          class="absolute pin-y w-24 text-white flex flex-col ml-2"
          style="left: 100%"
        >
          <reorder-button @click.stop="moveUp(source)" v-if="pivot.row !== 1" key="1">&uarr;</reorder-button>
          <reorder-button @click.stop="moveDown(source)" v-if="pivot.row !== 4" key="2">&darr;</reorder-button>
          <reorder-button
            key="3"
            @click.stop="moveRight(source)"
            v-if="pivot.order !== row.length"
          >&rarr;</reorder-button>

          <reorder-button @click.stop="moveLeft(source)" v-if="pivot.order !== 1" key="4">&larr;</reorder-button>
          <reorder-button @click.stop="increaseSize" key="5" v-if="pivot.size !== 'large'">+</reorder-button>
          <reorder-button @click.stop="decreaseSize" key="6" v-if="pivot.size !== 'small'">-</reorder-button>
        </transition-group>
      </div>

      <h1
        class="font-sans text-white font-light leading-tight text-base text-left font-semibold mt-2 leading-tight text-xl pr-5"
      >{{ source.title }}</h1>
    </div>
  </base-button>
</template>

<script>
import caps from "./Caps";
import baseButton from "./BaseButton";
import playIcon from "./PlayIcon";
import reorderButton from "./ReorderButton";
import sources from "../store/sources";
import Axios from "axios";

export default {
  components: {
    caps,
    baseButton,
    playIcon,
    reorderButton
  },
  props: {
    source: {
      type: Object,
      required: true
    },
    disableClick: {
      default: false
    },
    showAdminControls: Boolean
  },
  data() {
    return {
      state: "default"
    };
  },

  methods: {
    ...sources.mapMutations(["updateSource"]),
    ...sources.mapActions(["moveRight", "moveLeft", "moveUp", "moveDown"]),

    increaseSize() {
      let nextSizes = {
        small: "medium",
        medium: "large",
        large: "small"
      };

      this.updateSource({
        ...this.source,
        pivot: {
          ...this.source.pivot,
          size: nextSizes[this.source.pivot.size]
        }
      });
    },
    decreaseSize() {
      let prevSizes = {
        small: "large",
        medium: "small",
        large: "medium"
      };

      this.updateSource({
        ...this.source,
        pivot: {
          ...this.source.pivot,
          size: prevSizes[this.source.pivot.size]
        }
      });
    },

    handleImgLoad() {
      this.state = "loaded";
    },
    handleClick() {
      this.$router.push("/sources/" + this.source.id);
    },
    move(a, b) {
      //   alert("here");
    }
  },

  computed: {
    ...sources.mapGetters(["rows"]),
    sizes() {
      return {
        small: "6.6vh",
        medium: "9.1vh",
        large: "14vh"
      };
    },
    row({ rows, pivot }) {
      return rows[pivot.row - 1];
    },
    style({ category, source, sizes }) {
      return {
        "--category-color": category.color,
        width: `calc(${sizes[source.pivot.size]} * 1.6)`,
        flex: "0 0 auto"
      };
    },
    pivot({ source }) {
      return source.pivot;
    },
    loaded({ state }) {
      return state === "loaded";
    },
    thumbnailDimensions({ width }) {
      return { width: `${width}rem`, height: `${width * 0.56}rem` };
    },
    category({ source }) {
      return source.category;
    }
  }
};
</script>
