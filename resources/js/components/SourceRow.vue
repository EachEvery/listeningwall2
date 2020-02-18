<template>
  <transition-group
    name="list-complete"
    tag="div"
    :id="`row-${rowNumber}`"
    :class="{ 'animate-left': animateLeft, 'animate-right': animateRight }"
    class="w-full flex flex-no-wrap overflow-x-auto overflow-y-hidden mb-5 scrolly-row"
  >
    <source-card
      class="mx-16"
      v-for="source in sources"
      :source="source"
      :key="source.id"
      :show-admin-controls="adminMode"
    />
    <div :key="randomString()" class="w-5 flex" style="flex: 0 0 auto;"></div>
  </transition-group>
</template>
<style scoped lang="scss"></style>
<script>
import sourceCard from "./SourceCard";
import Axios from "axios";
import sources from "../store/sources";
import $ from "jquery";
import system from "../store/system";
import randomString from "../functions/randomString";

export default {
  components: {
    sourceCard
  },

  props: {
    row: Array,
    rowNumber: Number,
    shouldAnimate: Boolean
  },

  methods: {
    randomString,
    ...sources.mapMutations(["replaceSources"]),

    nextLeft() {
      return this.$container.scrollLeft() === 0
        ? this.scrollWidth - this.$container.parent().innerWidth()
        : 0;
    },

    scroll() {
      if (!this.shouldAnimate) return;

      this.$container.animate(
        {
          scrollLeft: this.nextLeft()
        },
        this.animationTime
      );
    }
  },

  computed: {
    ...system.mapState(["adminMode"]),

    animationTime() {
      let options = [4, 8, 16, 7, 13, 9];

      return _.sample(options) * 3 * 1000;
    },
    pauseTime() {
      return _.sample([1, 0, 3, 2]) * 1000;
    },
    animateLeft({ rowNumber }) {
      return rowNumber % 2 !== 0;
    },
    animateRight({ animateLeft }) {
      return !animateLeft;
    },
    rowLength({ row }) {
      return row.length;
    },
    sources({ row }) {
      return row.sort((a, b) => a.pivot.order - b.pivot.order);
    },
    $container({ rowNumber }) {
      return $(`#row-${rowNumber}`);
    },
    scrollWidth({ $$container }) {
      return this.$container[0].scrollWidth;
    }
  },
  mounted() {
    if (true) {
      if (this.animateLeft) {
        this.$container.scrollLeft(this.scrollWidth);
      }

      this.scroll();

      this.interval = setInterval(() => {
        this.scroll();
      }, this.animationTime + this.pauseTime);
    }
  }
};
</script>
