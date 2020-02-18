<template>
  <div class="flex h-full flex-col">
    <page-heading class="mt-16">
      <span class="font-sans uppercase">{{__('Share Your Voice')}}</span>
    </page-heading>

    <div class="relative mt-16 self-center" style="margin-top: 8rem">
      <div
        class="inline-block w-auto dark-overlay absolute overflow-hidden pin-t transition-slow"
        id="viewport"
        style="height: 80rem;"
        :style="videoContainerStyle"
      >
        <webcam
          :key="webcampComponentKey"
          ref="webcam"
          style="filter:grayscale(100%); width: 48rem"
          :video-style="`transform: scale(${1 + multiply(.7)}) translateY(${multiply(6, true)}rem) translateX(${multiply(.1)}rem); transform-origin: center;`"
          @finish="handleVideoEnd"
          @timeupdate="updateSlider"
        />
      </div>

      <video-poem
        :class="videoPoemClass"
        :words="words"
        class="absolute w-full h-full pin-t transition-slow"
        scale="1"
        style="margin-top: 20rem"
      />

      <div
        class="absolute pin-b mb-24 flex pin-b w-full flex justify-center transition"
        :class="buttonContainerClass"
      >
        <base-button
          class="text-white flex flex-col self-center relative"
          :class="recordButtonClass"
          @click.prevent="startRecording"
        >
          <div
            class="w-16 h-16 bg-white absolute pin-t rounded-full mx-auto"
            style="transform: translateX(2.65rem) translateY(1.4rem)"
          ></div>

          <progress-ring
            :stroke="multiply(2)"
            :radius="multiply(24)"
            :progress="ringPercentage"
            class="self-center"
          />

          <caps class="text-center mt-2 leading-normal w-24" style="width: 9.24rem">
            <template v-if="!isVideoRecording">{{__('Tap to Begin\nCountdown')}}</template>

            <template v-else>{{__('Tap to End\nEarly')}}</template>
          </caps>
        </base-button>
        <h3
          :class="countdownClass"
          class="text-white text-8xl text-shadow self-center absolute pin-x text-center"
        >{{countdownSeconds}}</h3>
      </div>

      <base-button
        class="text-white flex flex-col absolute pin-t pin-r -mr-10 mt-24x"
        style="transform: translateY(16rem)"
        :class="resetButtonClass"
        @click.prevent="resetVideo"
      >
        <refresh-icon class="w-16 h-16 self-center" />
        <caps class="text-center mt-5 leading-normal">{{__('Record\nAgain')}}</caps>
      </base-button>
    </div>

    <div class="absolute" style="right: 110%">
      <video
        :src="videoUrl"
        v-if="videoUrl"
        ref="video"
        style="width: 640px; height: 480px;"
        @loadeddata="generateSnapshot"
        crossorigin="anonymous"
      ></video>
      <canvas ref="canvas" style="width: 640px; height: 480px;" crossorigin="anonymous"></canvas>
    </div>

    <div
      class="transition-slow relative z-50 text-white flex flex-col"
      :class="videoControlsClass"
      style="padding: 0 15rem; margin-top: -17rem"
    >
      <div class="flex">
        <base-button class="mr-5" @click.prevent="play">
          <play-icon class="w-16 h-16 text-white" />
        </base-button>

        <input
          type="range"
          class="flex-grow self-center"
          v-model="sliderPercentage"
          value="0"
          @mousedown="pauseVideo"
        />
      </div>

      <spinner v-if="uploading" class="w-12 h-12 text-white self-center mt-16" />

      <primary-button
        v-if="!uploading"
        class="bg-white mt-16 max-w-xs self-center"
        color="#000"
        @click.prevent="uploadToS3"
      >{{__('Save Video')}}</primary-button>

      <base-button
        :class="{'invisible': uploading, 'opacity-0': uploading}"
        :disabled="uploading"
        @click.prevent="back"
        class="underline self-center text-white max-w-xs tracking-tight mt-5 transition"
      >{{__('Finish Without Video')}}</base-button>
    </div>
  </div>
</template>
<script>
import webcam from "../components/WebcamCapture";
import pageHeading from "../components/PageHeading";
import videoPoem from "../components/VideoPoem";
import builder from "../store/builder";
import baseButton from "../components/BaseButton";
import progressRing from "../components/ProgressRing";
import refreshIcon from "../components/RefreshIcon";
import playIcon from "../components/PlayIcon";
import caps from "../components/Caps";
import delay from "../functions/delay.js";
import random from "../functions/randomString.js";
import primaryButton from "../components/PrimiaryButton";
import uploadBlob from "../functions/uploadBlob.js";
import spinner from "../components/Spinner";
import html2canvas from "html2canvas";
import multiply from "../functions/multiply.js";
import __ from "../functions/translate";

const videoSeconds = 10;

export default {
  components: {
    webcam,
    pageHeading,
    videoPoem,
    baseButton,
    progressRing,
    caps,
    refreshIcon,
    playIcon,
    primaryButton,
    spinner
  },
  data() {
    return {
      state: "default",
      secondsRemaining: videoSeconds,
      videoData: undefined,
      webcampComponentKey: random(8),
      sliderPercentage: 0,
      videoLoaded: false,
      countdownSeconds: 3
    };
  },
  watch: {
    sliderPercentage: function() {
      this.$refs.webcam.updateCurrentTimePercentage(this.sliderPercentage);
    }
  },
  computed: {
    ...builder.mapState(["words", "videoUrl"]),
    countingDown({ state }) {
      return state === "countdown";
    },
    countdownClass({ countingDown }) {
      return {
        "opacity-0": !countingDown,
        "bounce-number": countingDown,
        invisible: !countingDown
      };
    },
    recordButtonClass({ countingDown }) {
      return {
        "opacity-0": countingDown
      };
    },
    uploading({ state }) {
      return state === "uploading";
    },
    videoControlsClass({ hasRecording, uploading }) {
      return {
        "opacity-0": !hasRecording && !uploading,
        invisible: !hasRecording && !uploading
      };
    },
    resetButtonClass({ hasRecording, uploading }) {
      return {
        "opacity-0": !hasRecording && !uploading,
        invisible: !hasRecording && !uploading
      };
    },
    videoContainerStyle({ hasRecording }) {
      return {
        transform: hasRecording ? "translateY(-12rem) scale(.8)" : "none"
      };
    },
    isVideoRecording({ secondsRemaining }) {
      return secondsRemaining < videoSeconds;
    },
    videoPoemClass({ hasRecording }) {
      return {
        "opacity-0": hasRecording,
        invisible: hasRecording
      };
    },
    ringPercentage({ secondsRemaining }) {
      return (secondsRemaining / videoSeconds) * 100;
    },
    hasRecording({ videoData }) {
      return videoData !== undefined;
    },
    buttonContainerClass({ hasRecording }) {
      return {
        "opacity-0": hasRecording,
        invisible: hasRecording
      };
    }
  },
  methods: {
    __,
    ...builder.mapMutations(["setVideoUrl", "setThumbnailUrl"]),
    multiply,

    async uploadToS3() {
      this.state = "uploading";

      let videoUrl = await uploadBlob(this.videoData);
      this.setVideoUrl(videoUrl);
    },
    handleVideoLoaded() {
      this.videoLoaded = true;
    },

    async generateSnapshot() {
      let ctx = this.$refs.canvas.getContext("2d");

      this.$refs.canvas.width = 640;
      this.$refs.canvas.height = 480;
      ctx.drawImage(this.$refs.video, 0, 0, 640, 480);

      let blob = await this.getBlob(this.$refs.canvas);
      let url = await uploadBlob(blob, ".jpg", "thumbnails/");
      this.setThumbnailUrl(url);
      this.back();
    },
    back() {
      this.$router.go(-1);
    },
    pauseVideo() {
      this.$refs.webcam.pause();
    },
    updateSlider({ currentTime, duration }) {
      if (this.hasRecording) {
        this.sliderPercentage = (currentTime / duration) * 100;
      }
    },
    getBlob(canvas) {
      return new Promise(resolve => {
        canvas.toBlob(
          function(blob) {
            console.log(blob, "blob");
            resolve(blob);
          },
          "image/jpeg",
          1
        );
      });
    },
    play() {
      this.$refs.webcam.play();
    },
    resetVideo() {
      this.videoData = undefined;
      this.thumbnailData = undefined;
      this.webcampComponentKey = random(8);
    },
    async handleVideoEnd({ videoBlob, thumbnailBlob }) {
      this.videoData = videoBlob;

      await delay(300);
      this.secondsRemaining = videoSeconds;
    },
    countdown() {
      return new Promise((resolve, reject) => {
        let initialCount = 3;
        this.countdownSeconds = initialCount;

        this.state = "countdown";

        let interval = setInterval(() => {
          if (this.countdownSeconds === 1) {
            clearInterval(interval);
          } else {
            this.countdownSeconds--;
          }
        }, 1000);

        setTimeout(() => {
          resolve();
        }, initialCount * 1000 - 200);
      });
    },
    async startRecording() {
      if (this.isVideoRecording) {
        clearInterval(this.videoInterval);

        this.$refs.webcam.stop();
        return;
      }

      await this.countdown();

      this.state = "default";

      let videoLenMs = videoSeconds * 1000;
      this.$refs.webcam.record();
      this.secondsRemaining = this.secondsRemaining - 1;

      // this.grabThumbnail();

      this.videoInterval = window.setInterval(async () => {
        console.log(this.secondsRemaining);
        if (this.secondsRemaining === 0) {
          clearInterval(this.videoInterval);
          await delay(1000);
          this.$refs.webcam.stop();
          return;
        } else {
          this.secondsRemaining = this.secondsRemaining - 1;
        }
      }, 1000);
    }
  }
};
</script>
<style lang="scss">
input[type="range"] {
  -webkit-appearance: none; /* Hides the slider so that custom slider can be made */
  background: transparent;
  padding: 1rem 0;

  &::-webkit-slider-thumb {
    -webkit-appearance: none;
  }
  &:focus {
    outline: none; /* Removes the blue border. You should probably do some kind of focus styling for accessibility reasons though. */
  }
  &::-webkit-slider-thumb {
    -webkit-appearance: none;
    height: 2em;
    width: 2em;
    margin-top: -0.75rem;
    border-radius: 50%;
    background: #ffffff;
    cursor: pointer;
  }
  &::-webkit-slider-runnable-track {
    width: 100%;
    height: 0.2rem;
    cursor: pointer;
    background: #fff;
    background: #fff;
  }
}

.dark-overlay {
  position: relative;

  &::after {
    content: "";
    position: absolute;
    top: 0;
    right: 0;
    left: 0;
    bottom: 0;
    background: #000;
    opacity: 0.2;
  }
}

.bounce-number {
  animation: bounceNumber 970ms 3, fadeOut 3s 1;
  animation-fill-mode: forwards;
}
@keyframes fadeOut {
  80% {
    opacity: 7;
  }
  100% {
    opacity: 0;
    display: none;
  }
}
@keyframes bounceNumber {
  50% {
    transform: scale(1.2);
  }
  100% {
    transform: scale(1);
  }
}
</style>