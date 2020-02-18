<template>
  <spin-icon class="spin"/>
</template>
<script>
import spinIcon from "./SpinIcon";
export default {
  components: {
    spinIcon
  }
};
</script>
<style lang="scss" scoped>
.spin {
  animation: 1s spin infinite;
}

@keyframes spin {
  from {
    transform: rotate(0deg);
  }

  to {
    transform: rotate(360deg);
  }
}
</style>