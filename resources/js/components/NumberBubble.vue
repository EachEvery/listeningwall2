<template>
  <div
    class="flex w-8 h-8 rounded-full font-sans font-extrabold text-lg justify-center bg-green text-white"
  >
    <span class="self-center inline-block">
      <slot/>
    </span>
  </div>
</template>
<script>
export default {
  props: {
    offset: {
      type: Number,
      defeault: 0
    }
  }
};
</script>

