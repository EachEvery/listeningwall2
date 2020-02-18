<template>
  <div class="transition-slow" :class="videoClass">
    <video
      id="video"
      class="video-js vjs-default-skin"
      playsinline
      :style="videoStyle"
      @timeupdate="emitTimeupdate"
    />
  </div>
</template>
<style>
.vjs-control-bar {
  visibility: hidden !important;
}
</style>
<script>
import video_css from "video.js/dist/video-js.min.css";
import videojs from "video.js";
import delay from "../functions/delay.js";
import "webrtc-adapter";
import RecordRTC from "recordrtc";
import record_css from "videojs-record/dist/css/videojs.record.css";
import Record from "videojs-record/dist/videojs.record.js";
import $ from "jquery";

export default {
  props: {
    videoStyle: String
  },
  data() {
    return {
      state: "default",
      canvasSize: {
        width: 0,
        height: 0
      },
      options: {
        controls: true,
        autoplay: false,
        fluid: false,
        loop: false,
        width: 320,
        height: 240,
        controlBar: {
          volumePanel: false
        },
        plugins: {
          record: {
            audio: true,
            video: true,
            debug: true
          }
        }
      }
    };
  },
  computed: {
    isReady({ state }) {
      return state === "ready";
    },
    videoClass({ isReady }) {
      return {
        "opacity-0": !isReady
      };
    },
    canvasStyle({ canvasSize }) {
      return {
        width: canvasSize.width + "px",
        height: canvasSize.height + "px"
      };
    }
  },
  methods: {
    updateCurrentTimePercentage(perc) {
      let multiple = perc / 100;

      if (this.player.paused()) {
        this.player.currentTime(this.player.record().getDuration() * multiple);
      }
    },
    emitTimeupdate(e) {
      this.$emit("timeupdate", {
        currentTime: e.target.currentTime,
        duration: e.target.duration
      });
    },
    async record() {
      $(".vjs-icon-record-start").trigger("click");
    },
    stop() {
      $(".vjs-icon-record-stop").trigger("click");
    },
    play() {
      this.player.play();
    },
    pause() {
      this.player.pause();
    },
    init() {
      this.player = videojs("video", this.options);

      // device is ready
      this.player.on("deviceReady", () => {
        this.$emit("ready");
      });

      // user clicked the record button and started recording
      this.player.on("startRecord", () => {
        this.$emit("start");
      });

      // user completed recording and stream is available
      this.player.on("finishRecord", async e => {
        this.$emit("finish", {
          videoBlob: this.player.recordedData
        });
      });

      // error handler
      this.player.on("error", (element, error) => {
        this.$emit("error", error);
      });

      this.player.on("deviceError", () => {
        this.$emit("error", {
          message: "Device error!",
          code: this.player.deviceErrorCode
        });
      });

      this.$nextTick(() => {
        $(".vjs-record").trigger("click");
        setTimeout(() => {
          this.state = "ready";
        }, 800);
      });
    }
  },
  beforeDestroy() {
    this.state = "default";
  },
  destroyed() {
    this.player.record().destroy();
  },
  mounted() {
    this.init();
  }
};
</script>