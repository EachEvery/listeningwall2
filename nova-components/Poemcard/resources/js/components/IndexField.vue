<template>
  <button class="shadow inline-flex my-8" @click="handleClick">
    <poem-card :field="field" style="background:#fcfcfc;" />
  </button>
</template>

<script>
import PoemCard from "./PoemCard";

export default {
  components: {
    PoemCard
  },
  props: ["resourceName", "field", "resource"],
  methods: {
    handleClick() {
      this.$router.push(`/resources/responses/${this.field.value.id}`);
    }
  }
};
</script>
