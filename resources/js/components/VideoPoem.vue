<template>
  <div :style="{'transform': `scale(${scale}) translateX(6rem)`}" style="transform-origin: top">
    <div class="border-b border-transparent" style="width: 33.2575rem">
      <div
        v-for="index in 16"
        :key="index"
        class="h-10 border-t border-transparent row relative"
        :id="`row-${index}`"
      >
        <word
          v-for="word in getRowWords(`row-${index}`)"
          :id="word.id"
          :style="{left: `${word.left}px`, top: `0`}"
          :key="word.id"
          :word="word"
          class="no-clone z-100 absolute"
          :data-timestamp="word.timestamp"
          :data-word-id="word.id"
        />
      </div>
    </div>
  </div>
</template>
<script>
import word from "./VideoPoemWord";
export default {
  components: {
    word
  },
  props: {
    words: {
      type: Array,
      required: true
    },
    scale: {
      default: 0.9
    }
  },
  methods: {
    getRowWords(row) {
      return this.words.filter(word => word.row === row.toString());
    }
  }
};
</script>