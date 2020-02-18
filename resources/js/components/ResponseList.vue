<template>
  <div
    class="grid grid-gap-8 mx-auto mb-24"
    style="justify-items: center; margin-bottom: 40rem; grid-template-columns: repeat(3, 0fr)"
    :style="{'--category-color': accentColor}"
  >
    <template v-for="(chunk) in chunk(responses, 3)">
      <div v-for="(response, index) in chunk" :key="response.id">
        <response
          :position="index"
          :response="response"
          @click="handleResponseClick(response)"
          :class="{'opacity-0': response.id === activeId}"
        />
      </div>
    </template>

    <portal to="modals">
      <transition name="fadeUp">
        <div
          class="absolute pin flex items-center justify-center"
          style="background: rgba(0,0,0,.8); z-index: 3000"
          @click.self="activeId = undefined"
          v-if="anyActive"
        >
          <div
            class="transition-slow relative"
            style="width: 40rem; height: 60rem; perspective: 1000px; transform-style: preserve-3d; backface-visibility: visible"
            :style="{'transform': sendFormShowing ? 'rotate3d(0, 1, 0, 180deg)' : 'none', height: activeResponse.title.length > 15 ? '64rem': '60rem'}"
          >
            <div
              class="bg-white absolute px-16 py-24 pin bg-blue"
              style="transform: rotate3d(0, 1, 0, 180deg);-webkit-backface-visibility: hidden;"
              @click="sendFormShowing = !sendFormShowing"
            >
              <share-poem />
            </div>

            <div
              class="bg-white absolute px-16 py-12 pin"
              style="-webkit-backface-visibility: hidden;"
            >
              <base-button
                class="absolute pin-r pin-t text-5xl  mr-4 text-black cursor font-light leading-none mr-4 transition"
                style="font-size: 6rem"
                :class="{'opacity-0': showingWordInfo}"
                @click="activeId = undefined"
              >&times;</base-button>

              <div
                class="font-black text-black flex flex-col items-center mb-12 transition"
                :class="{'opacity-0': showingWordInfo}"
              >
                <span
                  v-if="activeResponse.title"
                  class="leading-tight mb-4 text-center uppercase"
                  style="font-size: 2.6rem"
                >{{activeResponse.title}}</span>
                <span
                  v-if="activeResponse.author"
                  class="uppercase text-lg"
                >by {{activeResponse.author}}</span>
              </div>

              <poem-card
                :words="activeResponse.words"
                @state-change="handlePoemCardStateChange"
                :published-height="publishedHeight"
              />

              <div
                class="flex justify-center flex-col items-center transition"
                :class="{'opacity-0': showingWordInfo}"
              >
                <!-- <base-button
                  @click="sendFormShowing = !sendFormShowing"
                  class="bg-green text-black rounded-full px-8 font-black py-3 tracking-wide"
                >SEND TO A FRIEND</base-button>-->

                <base-button class="mt-6 flex items-center" @click="createFromThisPoem">
                  <span v-html="svg('duplicate', 'w-6 h-6 p-1')" class="mr-2"></span>
                  <caps>Create from this poem</caps>
                </base-button>
              </div>
            </div>
          </div>
        </div>
      </transition>
    </portal>
  </div>
</template>
<script>
import caps from "./Caps";
import poemCard from "./PoemCard";
import response from "./Response";
import chunk from "../functions/chunk.js";
import baseButton from "./BaseButton";
import sharePoem from "./SharePoem";
import builder from "../store/builder";
import $ from "jquery";

export default {
  components: {
    caps,
    poemCard,
    response,
    baseButton,
    sharePoem
  },
  props: {
    responses: {
      default: () => []
    },
    accentColor: {
      default: "#888"
    },
    allowClicks: Boolean
  },

  data() {
    return {
      state: "default",
      poemCardState: "",
      activeId: undefined,
      canClickOutside: false,
      sendFormShowing: false,
      scrollOptions: {
        scrollX: false,
        scrollY: true,
        deceleration: 0.0000003,
        momentum: false
      }
    };
  },

  watch: {
    activeId() {
      clearTimeout(this.timeout);

      this.timeout = setTimeout(() => {
        this.canClickOutside = this.anyActive;
      }, 400);
    }
  },

  methods: {
    ...builder.mapActions(["copyToBuilder"]),
    chunk,
    createFromThisPoem() {
      this.copyToBuilder(this.activeResponse);

      this.$nextTick(() => {
        this.handleClose();

        setTimeout(() => {
          $("#launch-builder").trigger("click");
        }, 800);
      });
    },
    handlePoemCardStateChange(state) {
      this.poemCardState = state;
    },
    handleResponseClick(r) {
      if (this.allowClicks) {
        this.activeId = r.id;
      }
    },
    handleClickOutside() {
      if (this.canClickOutside) {
        this.activeId = undefined;
      }
    },

    isActive(response) {
      return +response.id === +this.activeId;
    },
    setState(state) {
      this.state = state;
    },
    setActiveResponse(id) {
      this.activeId = id;
    },
    getPosition(index) {
      let pos = index + 4;

      if (Number.isInteger(pos / 3)) {
        return 3;
      }

      if (Number.isInteger(pos / 2)) {
        return 2;
      }
    },
    handleClose() {
      this.activeId = undefined;
    }
  },
  computed: {
    publishedHeight({ activeResponse }) {
      if (!activeResponse.published_height) {
        return undefined;
      }
      return +activeResponse.published_height;
    },
    showingWordInfo({ poemCardState }) {
      return poemCardState === "showWordInfo";
    },
    activeResponse({ activeId }) {
      return this.responses.find(r => r.id === activeId);
    },

    anyActive({ activeId }) {
      return activeId !== undefined;
    }
  }
};
</script>
