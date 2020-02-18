<template>
  <div class="text-white bg-black-alpha dark z-50">
    <div class="flex flex-row" v-for="(keyChunks, i) in chunk(keys, 3)" :key="i">
      <base-button
        class="flex justify-center text-sm p-2 border-white-30 border mr-1 mb-1"
        v-for="key in keyChunks"
        :key="key.code"
      >
        <span class="self-center text-white">{{key.key}}</span>
      </base-button>
    </div>
  </div>
</template>
<script>
import baseButton from "./BaseButton";
import chunk from "../functions/chunk.js";

export default {
  components: {
    baseButton
  },
  methods: {
    chunk
  },
  data() {
    return {
      keys: [
        { code: 49, key: "1" },
        { code: 50, key: "2" },
        { code: 51, key: "3" },
        { code: 52, key: "4" },
        { code: 53, key: "5" },
        { code: 54, key: "6" },
        { code: 55, key: "7" },
        { code: 56, key: "8" },
        { code: 56, key: "9" },
        { code: 48, key: "0" }
      ]
    };
  }
};
</script>

