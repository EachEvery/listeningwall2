<template>
  <div class="absolute bg-black shadow transition-slow" style="height: 26.29714rem; width: 55rem;">
    <video
      class="absolute pin-x pin-t w-full transition-slow"
      :class="mediaClass"
      style="height: 26.29714rem;"
      ref="media"
      @click="togglePlay"
      @ended="handleEnd"
      @timeupdate="handleTimeUpdated"
      @canplay="setDuration"
      :src="video"
      v-if="!isImage"
    />

    <img
      :src="isImage ? thumbnails[nextIndex] : thumbnail"
      class="absolute pin-x pin-t object-cover transition-slow w-full"
      :class="imgClass"
      ref="thumbnail"
      style="height: 26.29714rem;"
      @click="handleImgClick"
    />

    <img
      v-if="isImage"
      :src="currentThumbnail"
      class="absolute pin-x pin-t object-cover w-full"
      :class="{...imgClass, 'opacity-0': switchingImage, 'transition-slow': !switchingImage, 'transition': switchingImage  }"
      style="height: 26.29714rem;"
      @click="handleImgClick"
    />

    <div
      class="flex justify-center p-6 px-32 items-center z-10 absolute pin-b pin-x"
      v-if="!isImage"
      :style="controlContainerStyle"
    >
      <base-button class="mx-1" :class="playButtonClass" @click.prevent="togglePlay">
        <play-icon class="w-16 h-16 text-category-color shadow" />
      </base-button>

      <input
        type="range"
        class="flex-grow mx-10"
        v-model="sliderVal"
        @mousedown="handleRangeMouseDown"
        @mouseup="handleRangeMouseUp"
      />

      <span class="text-category-color uppercase font-bold" v-if="isAudio">Audio Only</span>

      <base-button
        class="mx-1"
        :class="soundButtonClass"
        @click.prevent="toggleSound"
        v-if="isVideo"
      >
        <sound-icon class="w-16 h-16 text-category-color shadow" />
      </base-button>
    </div>

    <div
      class="bg-blue absolute pin-x pin-b h-16 opacity-50"
      style="background: linear-gradient(0deg, rgba(0,0,0,1) 19%, rgba(0,0,0,0) 100%)"
    ></div>
  </div>
</template>

<script>
import playIcon from "./PlayIcon";
import soundIcon from "./SoundIcon";
import baseButton from "./BaseButton";
import activeSource from "../mixins/activeSource";

export default {
  components: {
    playIcon,
    soundIcon,
    baseButton
  },

  props: {
    controlContainerStyle: {
      default: () => {}
    },
    source: Object
  },
  mounted() {
    this.$nextTick(() => {
      this.$refs.thumbnail.click();
    });
  },
  data() {
    return {
      state: "default",
      currentTime: 0,
      totalTime: 0,
      sliderVal: 0,
      thumbnailIndex: 0,
      currentThumbnail: this.source.video.split(",")[0],
      switchingImage: false,
      swappingImage: false
    };
  },
  methods: {
    stopVideo() {
      if (this.isVideo) {
        this.controlVideo("pause");
      }
    },
    nextThumbnail() {
      if (this.thumbnails.length === 1 || this.swappingImage) {
        return;
      }

      let thumbnailIndex = this.thumbnailIndex + 1;

      this.switchingImage = true;
      this.swappingImage = true;

      setTimeout(() => {
        this.currentThumbnail = this.thumbnails[this.nextIndex];

        this.switchingImage = false;

        setTimeout(() => {
          this.thumbnailIndex = this.nextIndex;
          this.swappingImage = false;
        }, 800);
      }, 800);
    },
    handleRangeMouseDown() {
      this.$refs.media.pause();
    },
    handleImgClick() {
      if (!this.isImage) {
        this.togglePlay();
      } else {
        this.nextThumbnail();
      }
    },
    handleRangeMouseUp() {
      let perc = this.sliderVal / 100;

      let newTime = this.totalTime * perc;

      this.$refs.media.currentTime = newTime;
      this.current = newTime;

      this.controlVideo("play");
    },

    handleTimeUpdated(e) {
      this.updateTime(e.target.currentTime);
    },
    controlVideo(method) {
      this.$refs.media[method]();
    },
    updateTime(time) {
      this.currentTime = Math.round(time);
      this.totalTime = this.$refs.media.duration;
    },
    togglePlay() {
      this.state = this.isPlaying ? "default" : "play";
    },
    toggleSound() {
      this.state = this.isMuted ? "play" : "muted";
    },
    handleEnd() {
      this.state = "default";
    },
    setDuration(e) {
      this.totalTime = e.target.duration;
    },

    syncVieoState() {
      let video = this.$refs.media;
      video.muted = this.isMuted;

      if (this.isPlaying) {
        this.controlVideo("play");
      } else {
        this.controlVideo("pause");
        video.currentTime = 0;
      }
    }
  },
  watch: {
    state: function() {
      this.$nextTick(() => {
        this.syncVieoState();
      });
    },
    currentTime: function() {
      this.sliderVal = (this.currentTime / this.totalTime) * 100;
    }
  },

  computed: {
    thumbnail({ source }) {
      return source.thumbnail;
    },
    video({ source }) {
      return source.video;
    },
    nextIndex({ thumbnails, thumbnailIndex }) {
      let nextIndex = thumbnailIndex + 1;

      return thumbnails[nextIndex] === undefined ? 0 : nextIndex;
    },
    thumbnails({ video }) {
      return video.split(",");
    },
    isVideo({ extension }) {
      return ["mp4", "webm", "ogv"].includes(extension);
    },
    isAudio({ extension }) {
      return ["mp3", "wav"].includes(extension);
    },
    isImage({ extension }) {
      return ["jpeg", "jpg", "png", "tiff"].includes(extension);
    },
    extension({ video }) {
      return video
        .split(".")
        .pop()
        .toLowerCase();
    },

    scrubberStyle({ sliderVal }) {
      return { left: `${sliderVal}%` };
    },

    sliderTime({ sliderVal, totalTime }) {
      return Math.round(totalTime * (sliderVal / 100));
    },
    currentMinutes({ sliderTime }) {
      return Math.floor(sliderTime / 60);
    },

    currentSeconds({ sliderTime, currentMinutes }) {
      return sliderTime - currentMinutes * 60;
    },

    timestamp({ currentMinutes, currentSeconds }) {
      return `${this.pad(currentMinutes)}:${this.pad(currentSeconds)}`;
    },

    mediaClass({ isPlaying, isAudio }) {
      return {
        "opacity-0": !isPlaying || isAudio
      };
    },

    imgClass({ isPlaying, isAudio }) {
      return {
        "opacity-0": isPlaying && !isAudio
      };
    },

    soundButtonClass({ isMuted }) {
      return {
        "opacity-50": isMuted
      };
    },

    playButtonClass({ isPlaying, isAudio }) {
      return {
        "opacity-50": isPlaying
      };
    },

    isMuted({ state }) {
      return state === "muted";
    },

    isPlaying({ state, isMuted }) {
      return state === "play" || isMuted;
    }
  }
};
</script>
<style scoped>
input[type="range"] {
  -webkit-appearance: none;
  width: 100%;
}
input[type="range"]:focus {
  outline: none;
}
input[type="range"]::-webkit-slider-runnable-track {
  width: 100%;
  height: 0.125rem;
  cursor: pointer;
  box-shadow: 0px 0px 0.0625rem var(--category-color),
    0px 0px 0px var(--category-color);
  background: var(--category-color);
  border-radius: 0px;
  border: 0px solid var(--category-color);
}
input[type="range"]::-webkit-slider-thumb {
  box-shadow: 0px 0px 0px var(--category-color),
    0px 0px 0px var(--category-color);
  border: 0px solid var(--category-color);
  height: 1.25rem;
  width: 1.25rem;
  border-radius: 100%;
  background: var(--category-color);
  cursor: pointer;
  -webkit-appearance: none;
  margin-top: -0.59rem;
}
input[type="range"]:focus::-webkit-slider-runnable-track {
  background: #0d0d0d;
}
input[type="range"]::-moz-range-track {
  width: 100%;
  height: 0.125rem;
  cursor: pointer;
  box-shadow: 0px 0px 0.0625rem var(--category-color),
    0px 0px 0px var(--category-color);
  background: var(--category-color);
  border-radius: 0px;
  border: 0px solid var(--category-color);
}
input[type="range"]::-moz-range-thumb {
  box-shadow: 0px 0px 0px var(--category-color),
    0px 0px 0px var(--category-color);
  border: 0px solid var(--category-color);
  height: 1.25rem;
  width: 1.25rem;
  border-radius: 3.125rem;
  background: var(--category-color);
  cursor: pointer;
}
input[type="range"]::-ms-track {
  width: 100%;
  height: 0.125rem;
  cursor: pointer;
  background: transparent;
  border-color: transparent;
  color: transparent;
}
input[type="range"]::-ms-fill-lower {
  background: var(--category-color);
  border: 0px solid var(--category-color);
  border-radius: 0px;
  box-shadow: 0px 0px 0.0625rem var(--category-color),
    0px 0px 0px var(--category-color);
}
input[type="range"]::-ms-fill-upper {
  background: var(--category-color);
  border: 0px solid var(--category-color);
  border-radius: 0px;
  box-shadow: 0px 0px 0.0625rem var(--category-color),
    0px 0px 0px var(--category-color);
}
input[type="range"]::-ms-thumb {
  box-shadow: 0px 0px 0px var(--category-color),
    0px 0px 0px var(--category-color);
  border: 0px solid var(--category-color);
  height: 1.25rem;
  width: 1.25rem;
  border-radius: 3.125rem;
  background: var(--category-color);
  cursor: pointer;
  height: 0.125rem;
}
input[type="range"]:focus::-ms-fill-lower {
  background: var(--category-color);
}
input[type="range"]:focus::-ms-fill-upper {
  background: var(--category-color);
}
</style>