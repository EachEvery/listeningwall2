<template>
  <div v-html="html"></div>
</template>

<style>
.watermark {
  font-size: 18.4rem;
  line-height: 16rem;

  text-transform: uppercase;
  color: transparent;
  -webkit-text-stroke-color: rgba(255, 255, 255, 0.3);
  -webkit-text-stroke-width: 0.2rem;
}
</style>

<script>
import outlineHeading from "./OutlineHeading";
export default {
  components: {
    outlineHeading
  },
  props: {
    html: String
  },
  data() {
    return {
      strokeWidth: ".2rem",
      color: "rgba(255, 255, 255, .2)"
    };
  }
};
</script>