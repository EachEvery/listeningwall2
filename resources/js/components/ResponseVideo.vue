<template>
  <div style="filter:grayscale(100%)" :style="containerStyle">
    <img
      :src="thumbnail"
      class="absolute pin-x object-cover animate"
      :style="mediaStyle"
      :class="imgClass"
    >

    <video
      class="absolute pin-x w-full animate"
      :style="mediaStyle"
      :class="videoClass"
      ref="video"
      @loadeddata="handleLoadedData"
      @ended="handleEnd"
      :src="video"
    />

    <portal :to="`controls-${id}`">
      <button
        class="mx-1 active:opacity-50 focuus:outline-none transition"
        :class="videoButtonClass"
        @click.prevent="togglePlay"
      >
        <play-icon class="w-8 h-8 text-category-color"/>
      </button>

      <button
        class="mx-1 active:opacity-50 focuus:outline-none transition"
        :class="soundButtonClass"
        @click.prevent="toggleSound"
      >
        <sound-icon class="w-8 h-8 text-category-color"/>
      </button>
    </portal>
  </div>
</template>

<script>
import playIcon from "./PlayIcon";
import soundIcon from "./SoundIcon";
import baseButton from "./BaseButton";

export default {
  components: {
    playIcon,
    soundIcon,
    baseButton
  },
  props: {
    thumbnail: {},
    video: {},
    id: {}
  },
  data() {
    return {
      state: "default",
      videoHeight: 640,
      scale: 1,
      loaded: false
    };
  },

  methods: {
    handleLoadedData() {
      if (this.$refs.video === undefined) {
        return;
      }

      this.videoHeight = this.$refs.video.videoHeight;
      this.scale = this.$refs.video.clientHeight / this.videoHeight;
      this.loaded = true;
    },
    togglePlay() {
      this.state = this.isPlaying ? "default" : "playing";
    },
    toggleSound() {
      this.state = this.isMuted ? "playing" : "muted";
    },
    handleEnd() {
      this.state = "default";
    },
    syncVieoState() {
      let video = this.$refs.video;

      video.muted = this.isMuted;

      if (this.isPlaying) {
        video.play();
      } else {
        video.pause();
        video.currentTime = 0;
      }
    }
  },
  watch: {
    state: function() {
      this.$nextTick(() => {
        this.syncVieoState();
      });
    }
  },

  computed: {
    mediaStyle() {
      return {
        top: "50%",
        marginTop: `-${(this.videoHeight / 2) * this.scale}px`
      };
    },
    containerStyle() {
      return {
        transition: "300ms all ease",
        opacity: this.loaded ? 1 : 0
      };
    },
    videoClass({ isPlaying }) {
      return {
        "opacity-0": !isPlaying
      };
    },
    imgClass({ isPlaying }) {
      return {
        "opacity-0": isPlaying
      };
    },
    soundButtonClass({ isMuted }) {
      return {
        "opacity-50": isMuted
      };
    },
    videoButtonClass({ isPlaying }) {
      return {
        "opacity-50": isPlaying
      };
    },
    isMuted({ state }) {
      return state === "muted";
    },
    isPlaying({ state, isMuted }) {
      return state === "playing" || isMuted;
    }
  }
};
</script>
<style scoped>
.animated {
  transition: 600ms transform ease, 600ms opacity ease;
}
</style>