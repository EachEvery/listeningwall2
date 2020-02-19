<template>
  <panel-item :field="field">
    <template slot="value">
      <div class="inline-flex flex-col">
        <div class="flex">
          <button
            class="btn btn-default btn-primary"
            :class="{'opacity-25': started}"
            @click.prevent="download"
            :disabled="started"
          >{{ started ? "Generating image..." : "Download Poem" }}</button>
        </div>
        <div class="shadow-lg inline-flex my-8">
          <poem-card :field="poemField" :size="size / 100" field-name="Poem Card" ref="poemcard" />
        </div>

        <h3 class="border-b block text-center text-grey-600 pb-4 mb-6">Fix Word Overflow</h3>
        <div class="flex justify-between">
          <div class="flex">
            <button class="font-bold text-lg mx-2" @click="updatePublishedAtHeight(250)">&larr; Bump</button>
            <button
              class="font-bold text-lg mx-2"
              @click="updatePublishedAtHeight(-250)"
            >Bump &rarr;</button>
          </div>

          <div class="flex">
            <button class="mx-2" @click="updatePublishedAtHeight(25)">&larr; Nudge</button>
            <button class="mx-2" @click="updatePublishedAtHeight(-25)">Nudge &rarr;</button>
          </div>
        </div>
      </div>
    </template>
  </panel-item>
</template>
<script>
import PoemCard from "./PoemCard";
import Axios from "axios";
import { saveAs } from "file-saver";

import poemData from "../mixins/extractPoemDataFromNovaField";

export default {
  props: ["field"],
  mixins: [poemData],

  components: {
    PoemCard
  },

  data() {
    return {
      size: 80,
      state: "default",
      poemField: { ...this.field }
    };
  },

  methods: {
    async download() {
      this.state = "started";

      setTimeout(() => {
        saveAs(
          this.field.value.imageUrl,
          `#${this.poem.id} ${this.poem.title} by ${this.poem.author}.png`
        );

        setTimeout(() => {
          this.state = "default";
        }, 2000);
      }, 100);
    },
    async updatePublishedAtHeight(val) {
      let { data: poem } = await Axios.put(
        `/responses/${this.poemField.value.id}`,
        {
          response: {
            published_height:
              parseInt(this.poemField.value.height) + parseInt(val)
          }
        }
      );

      this.poemField.value = poem;
    }
  },

  computed: {
    started({ state }) {
      return state === "started";
    }
  }
};
</script>
