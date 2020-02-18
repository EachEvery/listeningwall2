<template>
  <base-button
    @click="handleClick"
    :disabled="disabled"
    :style="{'--category-color': color, 'font-size': '.25rem', 'margin-bottom': '.05rem' }"
    style="padding-top: .15rem"
    class="rounded-full py-1 px-2 bg-black"
  >
    <caps class="text-white flex">
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