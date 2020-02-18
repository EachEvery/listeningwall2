<template>
  <div class="h-full relative home">
    <div class="pt-16 flex flex-col pl-12 ml-2" @click="$emit('heading-clicked')">
      <h3
        style="font-size: 1.3rem"
        class="uppercase tracking-wide font-bold"
      >COLLECTION: {{ collection.name }}</h3>

      <h1 style="font-size: 7.125rem" class="flex justify-between items-end">
        <span class="font-sans uppercase font-black">STORIES</span>

        <clickable @click="handleAdminClick">
          <grid-icon
            class="w-5 h-5 text-white transition mr-5"
            :style="{'opacity': adminMode ? '1' : 0.1}"
          />
        </clickable>
      </h1>
    </div>

    <div
      class="relative h-full mt-8 flex flex-col source-row transition-slow"
      :style="containerStyle"
    >
      <source-row
        :row="row"
        v-for="(row, i) in rows"
        :row-number="i + 1"
        :key="i"
        :should-animate="!initialized"
      />
    </div>
  </div>
</template>

<style scoped>
.scrolly-row::-webkit-scrollbar {
  width: 0px; /* Remove scrollbar space */
  background: transparent; /* Optional: just make scrollbar invisible */
}
</style>

<script>
import sources from "../store/sources.js";
import chunk from "../functions/chunk.js";

import sourceCard from "../components/SourceCard";
import watermark from "../components/Watermark";
import sourceRow from "../components/SourceRow";

import Axios from "axios";

import welcome from "../components/Welcome";
import system from "../store/system";

import gridIcon from "../components/GridIcon";
import clickable from "../components/BaseButton";

export default {
  components: {
    sourceCard,
    watermark,
    sourceRow,
    welcome,
    gridIcon,
    clickable
  },

  props: {
    sourceOpen: Boolean
  },
  data() {
    return {
      state: "default",
      adminClickCount: 0
    };
  },
  methods: {
    ...system.mapMutations(["setAdminMode"]),

    handleAdminClick() {
      if (this.adminMode) {
        this.setAdminMode(false);

        return;
      }

      this.adminClickCount++;

      clearTimeout(this.adminTimeout);

      this.adminTimeout = setTimeout(() => {
        if (this.adminClickCount === 3) {
          this.setAdminMode(true);
        }

        this.adminClickCount = 0;
      }, 1000);
    }
  },
  watch: {
    initialized(isInitialized) {
      if (isInitialized) {
        $(".scrolly-row").stop();
      }
    },
    sources() {
      clearTimeout(this.updateTimeout);

      this.updateTimeout = setTimeout(() => {
        Axios.put(`/collections/${this.collection.id}/sources`, {
          pivots: this.pivots
        });
      }, 300);
    }
  },

  computed: {
    ...sources.mapState(["sources"]),

    ...sources.mapGetters(["rows"]),

    ...system.mapState(["initialized", "adminMode"]),

    containerStyle({ sourceOpen }) {
      if (sourceOpen) {
        return {
          transform: `translateY(5rem)`,
          opacity: 0
        };
      }

      return {};
    },

    pivots({ sources }) {
      return sources.map(s => s.pivot);
    },

    collection() {
      return window.collection;
    }
  }
};
</script>
