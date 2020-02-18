<template>
  <transition name="fadeUp">
    <div class="absolute pin-b pin-x select-none px-10 pb-10 pt-3 transition" :style="statusStyle">
      <network-helper @connected="handleConnected" @error="handleNetworkError" />

      <div class="flex justify-end">
        <div
          @click="handleTokenClick"
          class="inline-flex px-3 py-2 border-white-30 rounded p-3 rounded-full bg-black opacity-50 hover:opacity-100 transition cursor-pointer"
          v-if="state !== 'default'"
        >
          <div
            class="w-2 h-2 rounded-full mr-2 self-center flex-shrink-0"
            :style="{'background': bgColor}"
          ></div>
          <div class="inline-block text-sm uppercase">{{__('Connection Status')}}: {{dots}}</div>
        </div>
      </div>

      <div class="font-Normal text-xs mx-3 block mt-3 flex justify-between" ref="content">
        <span class="self-center">{{__(statusDescription)}}</span>
        <primary-button
          @click="reload"
          class="self-center text-xs p-2"
          color="#fff"
          style="padding: .5rem 1.5rem;"
        >Reload</primary-button>
      </div>
    </div>
  </transition>
</template>

<script>
import networkHelper from "./NetworkHelper";
import primaryButton from "./PrimiaryButton";
import __ from "../functions/translate";
export default {
  components: {
    networkHelper,
    primaryButton
  },
  data() {
    return {
      state: "default",
      open: false
    };
  },
  methods: {
    __,
    handleConnected({ downloadSpeed, uploadSpeed }) {
      if (downloadSpeed < 5.0 || uploadSpeed < 1.0) {
        this.state = "Weak";
      } else {
        this.state = "Normal";
      }
    },
    handleTokenClick() {
      this.open = !this.open;
    },
    handleNetworkError() {
      this.state = "Unstable";
    },
    networkWarning() {
      if (this.state === "default") {
        this.state = "showNetworkWarning";
      }
    },
    hideNetworkWarning() {
      this.state = "default";
    },
    reload() {
      window.location.reload(true);
    }
  },
  computed: {
    dots({ state }) {
      if (state === "Unstable") {
        return "•";
      }
      if (state === "Normal") {
        return "••••";
      }
      if (state === "Weak") {
        return "••";
      }
    },
    statusDescription({ state }) {
      if (state === "Unstable") {
        return;
        this.__(
          "Certain functions of Listening Wall will not work as expected, such as watching or uploading videos or publishing poems."
        );
      }
      if (state === "Normal") {
        return;
        this.__(
          "All functions of Listening Wall should be working as expected."
        );
      }
      if (state === "Weak") {
        return this.__(
          "You may experience intermittent issues when watching or uploading videos — most functionality of the Listening Wall should work as expected."
        );
      }
    },

    statusStyle() {
      return {
        transform: this.open ? "translateY(0)" : `translateY(4.5rem)`,
        background: this.open ? "black" : "transparent"
      };
    },

    bgColor({ state }) {
      if (state === "Unstable") {
        return "#c0392b";
      }
      if (state === "Normal") {
        return "#27ae60";
      }
      if (state === "Weak") {
        return "#f39c12";
      }

      return "#fff";
    }
  }
};
</script>

