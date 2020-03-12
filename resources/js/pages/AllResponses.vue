<template>
  <div class="overflow-y-scroll h-full">
    <div class="mt-16 flex flex-col mb-32">
      <h1 class="text-center tracking-wide" style="font-size: 3.125rem">
        <span class="font-sans uppercase">{{__('Community')}}</span>
      </h1>

      <div style="width: 9.625rem" class="h-2 bg-white rounded self-center mt-8"></div>
    </div>
    <response-list :responses="[...responses].reverse()" />
    <div style="height: 14rem"></div>
  </div>
</template>
<script>
import responseList from "../components/ResponseList";
import responses from "../store/responses.js";

import __ from "../functions/translate";

export default {
  methods: {
    __
  },
  components: {
    responseList
  },
  computed: {
    ...responses.mapState(["responses"])
  }
};
</script>