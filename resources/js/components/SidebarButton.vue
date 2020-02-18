<template>
  <base-button
    @click.prevent="handleClick"
    class="text-center font-bold text-white uppercase text-3xl mx-8 tracking-wide"
  >
    <span>{{__(label)}}</span>
  </base-button>
</template>
<script>
import baseButton from "./BaseButton";
import __ from "../functions/translate";

export default {
  props: {
    label: {
      type: String,
      default: "No Label Set"
    }
  },
  components: {
    baseButton
  },
  methods: {
    __,
    handleClick(e) {
      this.$emit("click", e);
    }
  }
};
</script>