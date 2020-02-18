<template>
  <iscroll-view :options="scrollOptions" class="scroll-view" ref="scroller">
    <slot/>
  </iscroll-view>
</template>
<script>
export default {
  data() {
    return {
      scrollOptions: {
        scrollX: false,
        scrollY: true,
        deceleration: 0.0000003
      }
    };
  }
};
</script>