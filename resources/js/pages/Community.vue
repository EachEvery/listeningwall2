<template>
  <div class="h-full absolute pin bg-black">
    <div class="pt-16 flex flex-col pl-12 ml-2" @click="$emit('heading-clicked')">
      <h3
        style="font-size: 1.3rem"
        class="uppercase tracking-wide font-bold"
      >COLLECTION: {{ collection.name }}</h3>

      <h1 style="font-size: 7.125rem">
        <span class="font-sans uppercase font-black">COMMUNITY</span>
      </h1>

      <div class="relative">
        <div class="overflow-auto py-16" style="height: 105rem">
          <response-list
            :responses="responsesInOrder"
            :allow-clicks="!builderOpen"
            ref="responseList"
          />
        </div>

        <div
          class="bg-blue absolute pin-x pin-t h-32"
          style="background: linear-gradient(180deg, rgba(0,0,0,1) 5%, rgba(0,0,0,0) 100%)"
        ></div>
        <div
          class="bg-blue absolute pin-x pin-b h-32"
          style="background: linear-gradient(0deg, rgba(0,0,0,1) 5%, rgba(0,0,0,0) 100%)"
        ></div>
      </div>
    </div>
  </div>
</template>
<script>
import collection from "../mixins/collection";
import responses from "../store/responses";
import responseList from "../components/ResponseList";

export default {
  mixins: [collection],
  components: {
    responseList
  },
  props: {
    builderOpen: Boolean
  },
  methods: {
    openResponse(id) {
      this.$refs.responseList.setActiveResponse(id);
    }
  },
  computed: {
    ...responses.mapState(["responses"]),
    responsesInOrder({ responses }) {
      return [...responses].slice(0, 200);
    }
  }
};
</script>