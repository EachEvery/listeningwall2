<template>
  <div
    class="absolute text-black text-center whitespace-no-wrap"
    v-click-outside="handleClickOutside"
  >
    <caps class="text-xs" :class="{invisible: isFromWordBank}">{{__('this word is from')}}</caps>
    <h3
      class="font-serif text-3xl font-light self-center leading-tight"
      :class="{invisible: isFromWordBank}"
    >{{isFromWordBank ? '' : word.source.title}}</h3>
    <caps
      :class="{invisible: isFromWordBank}"
      class="text-category-color mt-1 inline-block"
    >{{isFromWordBank ? '': word.source.category.name}}</caps>

    <div class="flex flex-col mt-24">
      <base-button
        class="relative self-center mt-2 transition"
        @click="goToSource"
        v-if="!isFromWordBank"
        :class="{'opacity-0': !imageLoaded}"
        :style="{transform: imageLoaded ? 'none' : 'translateY(.25rem)'}"
      >
        <img
          class="rounded-full w-24 h-24 border-2 border-category-color object-cover"
          @load="fadeImageIn"
          :src="word.source.thumbnail"
        />
        <caps
          class="absolute ml-5 pin-t mt-10 whitespace-no-wrap"
          style="left: 100%"
        >{{__('View Story')}}</caps>
      </base-button>
    </div>
  </div>
</template>

<script>
import BaseButton from "./BaseButton";
import Caps from "./Caps";
import DuplicateIcon from "./DuplicateIcon";
import DeleteIcon from "./DeleteIcon";
import __ from "../functions/translate";

export default {
  methods: {
    __,
    fadeImageIn() {
      this.state = "imageLoaded";
    },
    emit(event) {
      this.$emit(event);
    },
    goToSource() {
      this.$router.push(`/sources/${this.word.source.id}`);
    },
    handleClickOutside() {
      if (this.canClickOutside) {
        this.$emit("close");
      }
    }
  },
  data() {
    return {
      state: "default",
      canClickOutside: false
    };
  },
  mounted() {
    setTimeout(() => {
      this.canClickOutside = true;
    }, 300);
  },
  computed: {
    isFromWordBank({ word }) {
      return word.source.id === undefined;
    },
    imageLoaded({ state }) {
      return state === "imageLoaded";
    }
  },
  components: {
    BaseButton,
    Caps,
    DuplicateIcon,
    DeleteIcon
  },
  props: {
    word: {
      required: true
    }
  }
};
</script>