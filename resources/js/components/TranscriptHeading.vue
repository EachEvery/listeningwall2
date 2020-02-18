<template>
  <div class="inline-block" @click="emitClick">
    <outline-heading
      :color="color"
      class="text-3xl tracking-wide text-center transition-slow"
      :style="{transform: transcriptShowing ? 'translateY(5rem)' : 'none'}"
      stroke-width="0.07rem"
    >{{__('Transcript')}}</outline-heading>

    <p
      class="text-xl text-category-color max-w-xs mx-auto text-center mt-3 transition-slow"
      :class="{'opacity-0': transcriptShowing}"
    >{{__('Expand this Transcript to Create Your Own Response')}}.</p>
  </div>
</template>
<script>
import outlineHeading from "./OutlineHeading";
import __ from "../functions/translate";

export default {
  methods: {
    __,
    emitClick(e) {
      this.$emit("click", e);
    }
  },

  components: {
    outlineHeading
  },
  props: {
    transcriptShowing: {
      default: false
    },
    color: {
      default: "#fff"
    }
  }
};
</script>