<template>
  <div class="h-full">
    <response-list :responses="source.responses"/>
  </div>
</template>
<script>
import responseList from "../components/ResponseList";
import sources from "../store/sources.js";

export default {
  components: {
    responseList
  },
  computed: {
    ...sources.mapGetters(["withResponses"]),
    source({ withResponses }) {
      return withResponses.find(
        item => item.id === +this.$route.params.sourceId
      );
    }
  }
};
</script>