<template>
  <div :style="containerStyle" class="h-full">
    <iscroll-view :options="scrollOptions" class="scroll-view" ref="scroller">
      <div style="min-height: 123.75rem;" :style="sourceContainerStyle">
        <div
          class="bg-category-color-light absolute transition-slow flex flex-col pt-8"
          :style="backdropStyle"
          @click.prevent="toggleTranscript"
        ></div>

        <base-button
          @click.prevent="toggleTranscript"
          :style="buttonStyle"
          class="text-category-color absolute pin-r pin-t flex flex-col transition-slow"
        >
          <unexpand-icon v-if="transcriptShowing" class="w-8 h-8 self-center" />
          <expand-icon v-if="!transcriptShowing" class="w-8 h-8 self-center wiggle" />

          <caps class="mt-3 text-2xs self-center">{{buttonText}}</caps>
        </base-button>

        <transcript-heading
          @click.prevent="toggleTranscript"
          :style="transcriptHeadingStyle"
          :color="source.category.color"
          :transcript-showing="transcriptShowing"
          class="self-center absolute pin-t transition-slow"
        />

        <div
          class="flex flex-col max-w-sm absolute pin-t pin-l transition-slow justify-end items-center mb-10"
          :style="{...titleContainerStyle, 'height': '25rem', 'width': '28rem'}"
        >
          <caps class="text-category-color mb-4">{{source.category.name}}</caps>

          <h1
            class="font-serif font-light text-center"
            style="line-height: .9"
            :style="{...titleStyle, 'font-size': source.title.length < 70 ? '4rem': '3rem'}"
          >{{source.title}}</h1>

          <p
            class="text-center max-w-sm text-base mt-5 leading-normal tracking-tight"
          >{{source.text}}</p>
        </div>

        <transcript-prompt
          :style="transcriptPromptStyle"
          class="transition-slow"
          :class="{'pulse': words.length === 0 && transcriptShowing}"
        >{{words.length === 0? __('Select any word below to save it to your poem.') : __('Continue selecting words or visit "My Poem" to create your poem.')}}</transcript-prompt>

        <transcript
          :source="source"
          class="absolute pin-r pin-t transition-slow"
          :class="transcriptClass"
          style="right: 7.75rem; top: 38.40rem"
          ref="transcript"
        />

        <source-media
          :style="mediaStyle"
          :thumbnail="source.thumbnail"
          :video="source.video"
          class="transition-slow"
        />

        <div
          v-if="source.responses.length > 0"
          style="transform:translateY(100rem)"
          class="flex flex-col transition-slow"
          :class="{'opacity-0': transcriptShowing, 'invisible': transcriptShowing}"
        >
          <caps class="text-white self-center text-lg">{{__('Community')}}</caps>
          <base-button class="self-center mt-4" @click="scrollToResponses">
            <down-icon class="h-10 w-10 text-category-color" />
          </base-button>
        </div>
      </div>

      <response-list
        :responses="source.responses"
        class="mt-24"
        ref="responses"
        :accent-color="source.category.color"
      />

      <div style="height:20rem;"></div>
    </iscroll-view>
  </div>
</template>
<style>
.wiggle {
  animation-name: wiggle;
  animation-duration: 1s;
  animation-iteration-count: infinite;
}

.pulse {
  animation-duration: 2s;
  animation-name: fadeInOut;
  animation-timing-function: linear;
  animation-iteration-count: infinite;
}

@keyframes fadeInOut {
  0% {
    opacity: 0.25;
  }

  50% {
    opacity: 1;
  }

  100% {
    opacity: 0.25;
  }
}

@keyframes wiggle {
  0% {
    transform: rotate(0deg);
  }
  80% {
    transform: rotate(0deg);
  }
  85% {
    transform: rotate(8deg);
  }
  95% {
    transform: rotate(-8deg);
  }
  100% {
    transform: rotate(0deg);
  }
}
</style>
<script>
import sourcesModule from "../store/sources.js";
import builderModule from "../store/builder.js";
import transcript from "../components/Transcript";
import transcriptHeading from "../components/TranscriptHeading";
import transcriptPrompt from "../components/TranscriptPrompt";
import sourceMedia from "../components/SourceMedia";
import downIcon from "../components/DownIcon";
import unexpandIcon from "../components/UnexpandIcon";
import expandIcon from "../components/ExpandIcon";
import baseButton from "../components/BaseButton";
import responseList from "../components/ResponseList";
import caps from "../components/Caps";
import convertRemToPx from "../functions/convertRemToPx.js";
import $ from "jquery";
import multiply from "../functions/multiply.js";
import __ from "../functions/translate";

export default {
  components: {
    transcript,
    caps,
    sourceMedia,
    unexpandIcon,
    expandIcon,
    downIcon,
    transcriptPrompt,
    transcriptHeading,
    baseButton,
    responseList
  },
  data() {
    return {
      state: "init",
      transcriptHeight: 0,
      transcriptTop: 0,
      scrollOptions: {
        scrollX: false,
        scrollY: true,
        deceleration: 0.0000003,
        momentum: false
      }
    };
  },
  methods: {
    __,
    toggleTranscript() {
      this.state = this.transcriptShowing ? "default" : "transcriptShowing";
    },
    ensureDimensions() {
      this.transcriptHeight = $(this.$refs.transcript.$el).height();
      this.transcriptTop = $(this.$refs.transcript.$el).offset().top;
    },
    scrollToResponses() {
      this.$refs.scroller.iscroll.scrollToElement(
        this.$refs.responses.$el,
        2000,
        0
      );
    }
  },
  computed: {
    ...sourcesModule.mapGetters(["withResponses"]),
    ...builderModule.mapGetters(["sourceWords"]),
    words({ sourceWords }) {
      return sourceWords.filter(item => item.source.id === this.source.id);
    },
    source({ withResponses }) {
      return withResponses.find(
        item => item.id === +this.$route.params.sourceId
      );
    },
    contianerStyle({ source, communityShowing }) {
      return {
        transform: communityShowing ? "translateY(100%)" : "none",
        "--category-color": source.category.color,
        "--category-color-light": source.category.color_light
      };
    },
    transcriptShowing({ state }) {
      return state === "transcriptShowing";
    },
    communityShowing({ state }) {
      return this.$route.path.includes("community");
    },
    buttonStyle({ transcriptShowing }) {
      return {
        transform: transcriptShowing
          ? `translateX(-4rem) translateY(19rem)`
          : `translateX(-4rem) translateY(36rem)`
      };
    },
    backdropStyle({ transcriptShowing, transcriptHeight, transcriptTop }) {
      let bottom = transcriptHeight + transcriptTop - multiply(50);
      let transcriptHeadingHeight = 120;
      let height = bottom - transcriptHeadingHeight;

      return {
        display: transcriptHeight === 0 ? "none" : "block",
        width: "36.125rem",
        height: `${height}px`,
        right: "2.5rem",
        transformOrigin: "right top",
        transform: transcriptShowing
          ? `translateY(16.675rem)`
          : `translateY(33.425rem) scale(1.294, ${convertRemToPx(50) / height})`
      };
    },
    titleContainerStyle({ transcriptShowing }) {
      return {
        transformOrigin: "left",
        transform: transcriptShowing
          ? "translateY(8rem) translateX(2rem) scale(.8)"
          : "translateY(3.4rem) translateX(8rem) scale(1.2)"
      };
    },
    titleStyle({ transcriptShowing }) {
      return {};
    },
    transcriptHeadingStyle({ transcriptShowing }) {
      return {
        transform: transcriptShowing
          ? `translateX(37rem) translateY(19rem)`
          : `translateX(32rem) translateY(36rem)`
      };
    },
    transcriptPromptStyle({ transcriptShowing }) {
      return {
        position: "absolute",
        opacity: transcriptShowing ? 1 : 0,
        visibility: transcriptShowing ? "visible" : "hidden",
        transform: "translateX(41.5rem) translateY(28rem)"
      };
    },
    mediaStyle({ transcriptShowing, category }) {
      return {
        "--currentColor": category.color,
        position: "absolute",
        top: 0,
        left: 0,
        transform: transcriptShowing
          ? `translateY(25rem) translateX(-9rem) scale(.56)`
          : `translateY(45rem) translateX(12rem)`
      };
    },
    sourceContainerStyle({ transcriptHeight, transcriptTop }) {
      return {
        height: `${transcriptHeight + transcriptTop}px`
      };
    },
    transcriptClass({ transcriptShowing }) {
      return {
        "opacity-0": !transcriptShowing,
        invisible: !transcriptShowing
      };
    },
    buttonText({ transcriptShowing }) {
      return transcriptShowing ? "Close" : "Expand";
    },
    category({ source }) {
      return source.category;
    },
    containerStyle({ category }) {
      console.log(category.color_light, "color_light");
      return {
        "--category-color": category.color,
        "--category-color-light": category.color_light
      };
    }
  },
  mounted() {
    this.$nextTick(() => {
      this.ensureDimensions();
    });
  }
};
</script>