<template>
  <div class="px-4 py-2 bg-white text-black rounded-full inline-block text-lg">{{word.word}}</div>
</template>
<script>
import caps from "./Caps";

export default {
  components: {
    caps
  },
  props: {
    word: {
      type: Object,
      required: true
    }
  },
  computed: {
    category({ word }) {
      return word.source.category;
    }
  }
};
</script>
