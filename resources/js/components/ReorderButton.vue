<template>
  <clickable
    v-on="$listeners"
    v-bind="$props"
    class="w-4 h-4 text-white rounded-full justify-center items-center mb-3 flex-none"
    :class="{'opacity-25': disabled}"
  >
    <span class="text-xs w-2 h-2 mt-2">
      <slot />
    </span>
  </clickable>
</template>
<script>
import clickable from "./BaseButton";
export default {
  props: {
    disabled: {
      default: false
    }
  },
  components: {
    clickable
  }
};
</script>