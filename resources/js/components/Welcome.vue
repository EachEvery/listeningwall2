<template>
  <transition name="fadeUp">
    <div
      v-if="open"
      class="absolute pin z-50 h-screen flex justify-center items-center text-black"
      style="background: rgba(0,0,0,.8)"
      @click.self="handleClick"
    >
      <div
        class="bg-white border-green justify-center items-center flex flex-col text-center py-24"
        style="border-width: 1rem; width: 35rem;"
      >
        <img src="/images/welcome.png" class="w-full mb-10" style="width: 25rem;" />

        <h2 class="uppercase font-black">Welcome to the</h2>
        <h1 class="uppercase font-black mt-1">Listening Wall</h1>

        <p class="text-2xl font-medium tracking-tight mt-4 max-w-xs leading-normal">
          A tool for creating poetry
          <br />from texts, videos, images,
          <br />and more.
        </p>

        <base-button
          class="mt-12 rounded-full bg-black text-white font-black py-3 text-md px-4 shrink-when-active cursor-pointer"
          @click="handleClick"
        >GET STARTED</base-button>
      </div>
    </div>
  </transition>
</template>
<script>
import baseButton from "./BaseButton";

export default {
  components: {
    baseButton
  },
  methods: {
    handleClick() {
      this.$emit("close");
    }
  },
  props: {
    open: Boolean
  }
};
</script>
