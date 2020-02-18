<template>
  <base-button
    @click="handleClick"
    :disabled="disabled"
    :style="{'--category-color': color}"
    class="border-category-color border rounded-full py-4 px-12"
  >
    <caps class="text-category-color font-black">
      <slot />
    </caps>
  </base-button>
</template>

<script>
import baseButton from "./BaseButton";
import caps from "./Caps";
export default {
  props: {
    color: {
      default: "#000"
    },
    disabled: {
      default: false
    }
  },
  components: {
    baseButton,
    caps
  },
  methods: {
    handleClick(e) {
      this.$emit("click", e);
    }
  }
};
</script>