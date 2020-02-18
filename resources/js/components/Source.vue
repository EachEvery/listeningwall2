<template>
  <div
    style="background-color: #292929; height: 105rem; width: 63.2rem; transition: 600ms all ease;"
    class="p-2 absolute pin-r pin-b pl-16 pt-6 pr-8"
    :style="containerStyle"
  >
    <div class="absolute pin-t pin-x" style="height: 10rem" @click="$emit('heading-clicked')"></div>

    <caps
      class="text-white self-center text-base w-full text-left absolute pin-x pin-t pb-4"
      style="transform: rotate(90deg) translateY(-100%) translateX(10.5rem); transform-origin: 0 0;"
    >{{ source.category.name }}</caps>

    <h1 style=" height: 7rem;" class="pr-10" :style="titleStyle" :class="titleClass">
      <span class="font-sans font-black">{{source.title}}</span>
    </h1>

    <div style="height: 26.29714rem;" class="mt-8 mb-5"></div>

    <div class="flex justify-between">
      <span class="font-sans font-black leading-tight text-3xl block">
        About
        <br />This Story
      </span>

      <div class="relative -mt-8">
        <div class="overflow-auto" style="height: 35rem;">
          <p
            class="text-white py-10"
            :class="{'font-semibold': !hasShortDescription, 'leading-tight': hasShortDescription, 'leading-normal': !hasShortDescription}"
            :style="{width: hasShortDescription ? '41rem' : '23rem', fontSize: hasShortDescription ? '3rem' : '1.125rem' }"
          >{{source.text}}</p>
        </div>

        <div
          class="bg-blue absolute pin-x pin-t h-12"
          style="background: linear-gradient(180deg, rgba(41,41,41,1) 19%, rgba(41,41,41,0) 100%)"
        ></div>
        <div
          class="bg-blue absolute pin-x pin-b h-16"
          style="background: linear-gradient(0deg, rgba(41,41,41,1) 19%, rgba(41,41,41,0) 100%)"
        ></div>
      </div>
    </div>
  </div>
</template>

<script>
import builderModule from "../store/builder";
import caps from "../components/Caps";
import collection from "../mixins/collection";
import sourcesModule from "../store/sources";
import sourceMedia from "./SourceMedia";
import transcript from "../components/Transcript";
import activeSource from "../mixins/activeSource";

export default {
  mixins: [collection, activeSource],

  components: {
    caps,
    sourceMedia,
    transcript
  },

  computed: {
    ...builderModule.mapGetters(["sourceWords"]),

    titleStyle({ hasShortTitle, hasSuperLongTitle }) {
      return {
        width: hasShortTitle ? "30rem" : "auto",
        fontSize: hasSuperLongTitle ? "2rem" : "3rem",
        lineHeight: hasSuperLongTitle ? "2.8rem" : "initial"
      };
    },

    words({ sourceWords }) {
      return sourceWords.filter(item => item.source.id === this.source.id);
    },

    titleClass({ hasShortTitle }) {
      return { "max-w-md": hasShortTitle };
    },

    containerStyle({ category }) {
      return {
        "--category-color": category.color,
        "--category-color-light": category.color_light
      };
    }
  }
};
</script>