<template>
  <div class="flex relative h-8">
    <number-bubble class="absolute mr-4" style="right: 100%;">{{number}}</number-bubble>
    <caps class="self-center text-xs" :class="{'text-white': inverted}">
      <slot/>
    </caps>
  </div>
</template>
<script>
import numberBubble from "./NumberBubble";
import caps from "./Caps";
export default {
  components: {
    numberBubble,
    caps
  },
  props: {
    number: {
      type: String,
      required: true
    },
    inverted: {
      type: Boolean,
      default: false
    }
  }
};
</script>