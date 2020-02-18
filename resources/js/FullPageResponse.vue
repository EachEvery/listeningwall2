<template>
  <poem-card :field="field" :size="2" />
</template>
<script>
import PoemCard from "../../nova-components/Poemcard/resources/js/components/PoemCard.vue";
import poemData from "../../nova-components/Poemcard/resources/js/mixins/extractPoemDataFromNovaField";

export default {
  components: {
    PoemCard
  },
  mixins: [poemData],

  computed: {
    poemCard() {
      return window.poemCard;
    },
    field() {
      return {
        value: this.poemCard
      };
    }
  }
};
</script>