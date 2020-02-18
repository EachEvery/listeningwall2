<template>
  <div
    :style="containerStyle"
    class="bg-white text-black p-8 pt-12 absolute pin-b pin-r shadow-lg"
    :class="{ shadow: open }"
    style="width: 50rem; height: 77rem; transition: 600ms all ease; z-index: 100000"
    v-click-outside="handleClickOutside"
  >
    <h1 class="font-black uppercase text-center tracking-wide text-md">Common Words</h1>

    <div class="mx-auto mt-12" style="width: 35rem">
      <clickable
        v-for="word in words"
        :key="word.id"
        class="px-4 py-2 uppercase font-bold text-white rounded-full inline-block text-md mr-2 mt-3 relative"
        :class="{
                    'px-4': !selectedIds.includes(word.id),
                    'pl-6': selectedIds.includes(word.id),
                    'pr-2': selectedIds.includes(word.id),
                    'bg-green': selectedIds.includes(word.id),
                    'bg-grey-dark': !selectedIds.includes(word.id)
                }"
        @click="toggleSelected(word)"
      >
        <span
          v-html="svg('check-circle', 'w-4 h-4 text-white')"
          class="absolute pin-l ml-1 mt-1"
          style="margin-top: .075rem"
          :class="{ 'opacity-0': !selectedIds.includes(word.id) }"
        ></span>
        {{ word.word }}
      </clickable>
    </div>

    <div class="flex justify-center mt-16">
      <clickable
        :disabled="selectedIds.length === 0"
        class="bg-green rounded-full tracking-wide text-base font-black uppercase text-white px-6 py-4 transition"
        :class="{ 'opacity-25': selectedIds.length === 0 }"
        @click="addWords"
      >ADD SELECTED WORDS</clickable>
    </div>
  </div>
</template>
<script>
import builder from "../store/builder";
import clickable from "./BaseButton";
export default {
  components: {
    clickable
  },
  props: {
    open: Boolean
  },
  data() {
    return {
      canClickOutside: false,
      selectedIds: []
    };
  },
  methods: {
    addWords() {
      let words = this.commonWords.filter(w => this.selectedIds.includes(w.id));

      this.$emit("close");

      this.$emit("add", words);

      this.selectedIds = [];
    },
    handleClickOutside() {
      this.$nextTick(() => {
        if (this.canClickOutside) {
          this.$emit("close");
        }
      });
    },
    toggleSelected(word) {
      let index = this.selectedIds.findIndex(i => i === word.id);

      if (index < 0) {
        this.selectedIds.push(word.id);
      } else {
        this.selectedIds.splice(index, 1);
      }
    }
  },
  watch: {
    open() {
      setTimeout(() => {
        this.canClickOutside = this.open;
      }, 300);
    }
  },
  computed: {
    ...builder.mapGetters(["commonWords"]),
    words({ commonWords }) {
      return commonWords.filter(w => w.word.trim() !== "");
    },
    containerStyle({ open }) {
      return {
        transform: `translateY(${open ? "0" : "100"}%)`
      };
    }
  }
};
</script>
