<template>
  <svg :height="radius * 2" :width="radius * 2">
    <circle
      stroke="white"
      fill="transparent"
      :stroke-dasharray="circumference + ' ' + circumference"
      :style="{ strokeDashoffset }"
      :stroke-width="stroke"
      :r="normalizedRadius"
      :cx="radius"
      :cy="radius"
    ></circle>
  </svg>
</template>

<style lang="scss">
circle {
  transition: stroke-dashoffset 1s linear;
  transform: rotate(-90deg);
  transform-origin: 50% 50%;
}
</style>

<script>
export default {
  props: {
    radius: Number,
    progress: Number,
    stroke: Number
  },
  computed: {
    strokeDashoffset({ circumference, progress }) {
      return circumference - (progress / 100) * circumference;
    }
  },

  data() {
    const normalizedRadius = this.radius - this.stroke * 2;
    const circumference = normalizedRadius * 2 * Math.PI;

    return {
      normalizedRadius,
      circumference
    };
  }
};
</script>