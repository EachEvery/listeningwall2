<template>
  <div>
    <app-container v-if="state !== 'initializing'" class="overflow-hidden flex flex-col relative">
      <global-actions
        class="absolute z-50"
        @page-link-clicked="handlePageChange"
        :page="page"
        @poem-clicked="handlePoemClick"
        :poems-enabled="!builderOpen"
      />

      <transition name="fadeUp">
        <stories
          style="padding-left: 4.2rem; z-index: 10"
          v-if="page === 'stories'"
          :source-open="mountSourceDrawer"
          :welcome-open="welcomeOpen"
          :builder-open="builderOpen"
        />
        <community
          style="padding-left: 4.2rem; z-index: 10;"
          ref="community"
          v-if="page === 'community'"
          :builder-open="builderOpen"
        />
      </transition>

      <div :style="sourceStyle" class="transition-slow absolute pin z-50" style="left: 4rem;">
        <div class="absolute pin-t pin-x" style="height: 15rem" @click="closeStoriesDrawer"></div>

        <source-popup
          style="z-index: 102"
          v-if="source !== undefined"
          @heading-clicked="closeStoriesDrawer"
        />

        <transcript
          style="z-index: 103"
          v-if="source !== undefined"
          :open="transcriptOpen"
          @toggle="transcriptOpen = !transcriptOpen"
        />

        <source-media
          style="z-index: 104"
          v-if="source !== undefined"
          :source="source"
          :style="mediaStyle"
          ref="sourceMedia"
        />
      </div>

      <builder
        style="z-index: 105"
        :open="builderOpen"
        @toggle="builderOpen = !builderOpen"
        @go-to-source="switchStory"
        @publish="handlePublish"
      />

      <!-- <network-status /> -->

      <welcome @close="setInitialized(true)" :open="!initialized" style="z-index: 106;" />

      <portal-target name="modals" multiple></portal-target>
    </app-container>

    <portal-target name="sunkenPlace" class="opacity-0 invisible fixed pin-t"></portal-target>
  </div>
</template>
<script>
import appContainer from "./components/AppContainer";
import globalActions from "./components/GlobalActions";
import networkStatus from "./components/NetworkStatus";
import sidebarButton from "./components/SidebarButton";
import helpIcon from "./components/HelpIcon";
import builder from "./components/Builder";
import publishPoem from "./components/PublishPoem";
import welcome from "./components/Welcome";

import activeSource from "./mixins/activeSource";
import outlineHeading from "./components/OutlineHeading";
import primaryButton from "./components/PrimiaryButton";

import system from "./store/system.js";
import sources from "./store/sources.js";
import responses from "./store/responses.js";

import sourcePopup from "./components/Source";
import stories from "./pages/Stories";
import transcript from "./components/Transcript";
import sourceMedia from "./components/SourceMedia";
import community from "./pages/Community";

import IdleJs from "idle-js";

export default {
  components: {
    sourcePopup,
    appContainer,
    globalActions,
    sidebarButton,
    community,
    helpIcon,
    networkStatus,
    builder,
    stories,
    transcript,
    sourceMedia,
    welcome
  },

  mixins: [activeSource],

  data() {
    return {
      state: "initializing",
      transition: "fadeInOut",
      mode: "out-in",
      sourceDrawerOpen: false,
      transcriptOpen: false,
      builderOpen: false,
      welcomeOpen: false,
      page: "stories"
    };
  },

  methods: {
    ...sources.mapActions(["loadSources"]),
    ...responses.mapActions(["loadResponses"]),
    ...system.mapMutations(["setInitialized"]),
    handlePublish(response) {
      this.handlePageChange("community");
      this.builderOpen = false;

      setTimeout(() => {
        this.$refs.community.openResponse(response.id);
      }, 1000);
    },
    handlePoemClick() {
      this.builderOpen = !this.builderOpen;
    },
    handlePageChange(page) {
      this.closeStoriesDrawer();

      this.page = page;

      this.builderOpen = false;
    },

    async switchStory(id) {
      this.builderOpen = false;

      if (+this.$route.params.sourceId === +id) {
        return;
      }

      await this.closeStoriesDrawer();
      this.$router.push("/sources/" + id);
    },

    closeStoriesDrawer() {
      return new Promise(resolve => {
        if (!this.mountSourceDrawer) {
          resolve();
          return;
        }

        this.sourceDrawerOpen = false;

        setTimeout(() => {
          this.$router.push("/");
          resolve();
        }, 800);
      });
    }
  },
  watch: {
    source() {
      this.$nextTick(() => {
        this.sourceDrawerOpen = this.source !== undefined;
      });
    },
    mountSourceDrawer(val) {
      if (!val && this.$refs.sourceMedia) {
        this.$refs.sourceMedia.stopVideo();
      }
    },
    $route() {
      if (this.$route.name === "source" && this.page === "community") {
        this.page = "stories";
      }
    }
  },
  computed: {
    ...sources.mapGetters(["withResponses"]),
    ...system.mapState(["initialized"]),

    mediaStyle({ transcriptOpen, category }) {
      return {
        "--category-color": category.color,
        position: "absolute",
        top: "20%",
        right: "4rem",
        transform: transcriptOpen
          ? "scale(.5) translateX(-20rem) translateY(5rem)"
          : "none"
      };
    },

    sourceStyle({ mountSourceDrawer }) {
      return {
        transform: `translateY(${mountSourceDrawer ? "0" : "100%"})`
      };
    },

    mountSourceDrawer({ sourceDrawerOpen, source }) {
      return sourceDrawerOpen && source !== undefined;
    },

    source({ withResponses }) {
      return withResponses.find(
        item => item.id === +this.$route.params.sourceId
      );
    }
  },

  async mounted() {
    let idle = new IdleJs({
      idle: 60 * 1000, // idle time in ms
      events: ["mousemove", "keydown", "mousedown", "touchstart"], // events that will trigger the idle resetter
      onIdle: () => {
        let videosPlaying = $("video")
          .toArray()
          .filter(item => {
            return item.playing;
          });

        if (videosPlaying.length === 0 && !this.initialized) {
          this.$router.push("/");
          window.location.reload(true);
        }
      }, // callback function to be executed after idle time
      onActive: function() {}, // callback function to be executed after back form idleness
      onHide: function() {}, // callback function to be executed when window become hidden
      onShow: function() {}, // callback function to be executed when window become visible
      keepTracking: true, // set it to false if you want to be notified only on the first idleness change
      startAtIdle: false // set it to true if you want to start in the idle state
    });

    idle.start();

    await this.loadSources();
    await this.loadResponses();

    this.setInitialized(false);
    this.state = "default";
  }
};
</script>
