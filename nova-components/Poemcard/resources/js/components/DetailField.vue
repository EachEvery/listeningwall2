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
          <poem-card :field="field" :size="size / 100" field-name="Poem Card" ref="poemcard" />
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
    }
  },

  computed: {
    started({ state }) {
      return state === "started";
    }
  },

  data() {
    return {
      size: 80,
      state: "default"
    };
  }
};
</script>
