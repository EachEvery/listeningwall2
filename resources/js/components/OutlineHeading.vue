<template>
  <h1
    class="text-transparent"
    :style="{
            '-webkit-text-stroke-color': color,
            '-webkit-text-stroke-width': strokeWidth
        }"
  >
    <slot />
  </h1>
</template>

<script>
export default {
  props: {
    color: {
      default: "#000"
    },
    strokeWidth: {
      default: "0.09rem"
    }
  }
};
</script>
