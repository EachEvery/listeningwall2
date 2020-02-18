<template>
  <div
    :style="containerStyle"
    class="bg-white text-white p-8 pt-12 absolute pin-b pin-r shadow-lg"
    :class="{ shadow: open }"
    style="width: 50rem; height: 77rem; transition: 600ms all ease; z-index: 100000; background: #292929;"
    v-click-outside="handleClickOutside"
  >
    <div class="absolute pin-x pin-t" style="height: 8rem" @click="handleClickOutside"></div>

    <h1 class="font-black uppercase text-center tracking-wide text-md">PUBLISH YOUR POEM</h1>

    <div class="mx-auto mt-12" style="width: 24rem">
      <form
        ref="form"
        @submit.prevent="handleSubmit"
        autocomplete="off"
        autocorrect="off"
        spellcheck="false"
      >
        <text-field
          name="response[title]"
          label="Poem Title"
          placeholder="Poem Title"
          class="mt-5"
          ref="titleField"
          @next="handleTitleNext"
          @focus="handleTitleFocus"
          :initial-value="title"
          @change="handleTitleChange"
          :disabled="!defaultState"
          :required="true"
        />

        <text-field
          name="response[author]"
          label="Your Name"
          placeholder="Your Name"
          class="mt-5"
          ref="authorField"
          @change="handleNameChange"
          :initial-value="name"
          :disabled="!defaultState"
          :required="true"
        />

        <input type="hidden" name="response[words]" :value="JSON.stringify(wordsToSubmit)" />

        <input type="hidden" name="response[published_height]" :value="currentHeight" />

        <input type="hidden" name="blob" :value="blob" />

        <input
          type="hidden"
          :value="sendMeMyPoem ? 'yes' : 'no'"
          name="response[should_send_to_author]"
        />

        <csrf-field />

        <div class="items-center justify-center mt-12 flex">
          <clickable class="flex items-center" @click.prevent="sendMeMyPoem = !sendMeMyPoem">
            <div class="w-4 h-4 bg-green rounded text-black flex items-center justify-center mr-2">
              <span
                v-html="svg('check', 'w-3 h-3')"
                class="transition"
                :class="{
                                    'text-transparent': !sendMeMyPoem,
                                    'text-black': sendMeMyPoem
                                }"
              ></span>
            </div>
            <caps class="text-white">Text Me My Poem</caps>
          </clickable>
        </div>

        <div
          class="rounded-lg p-6 pt-5 mt-5 transition-slow relative"
          style="background: #3C3C3C"
          :class="{ 'opacity-0': !sendMeMyPoem }"
        >
          <text-field
            name="response[phone]"
            label="Your Phone Number"
            placeholder="Your Phone Number"
            keyboard-type="dialpad"
            @change="handlePhoneChange"
            :initial-value="phone"
            :disabled="!defaultState && !sendMeMyPoem"
            :required="sendMeMyPoem"
          />
        </div>

        <div
          class="flex justify-center transition"
          :style="{
                        transform: `translateY(${sendMeMyPoem ? '0' : '-7rem'})`
                    }"
        >
          <transition name="fade" mode="out-in">
            <primary-button
              key="1"
              color="#000"
              class="bg-white mt-16"
              v-if="defaultState"
            >{{ __("Publish") }}</primary-button>

            <spinner key="2" class="w-10 h-10 mt-16" v-else-if="loading" />

            <div class="flex mt-16" key="3" v-else-if="saved">
              <check-icon class="w-8 h-8 mr-3" />
              <caps class="self-center">
                {{
                __("Published")
                }}
              </caps>
            </div>
          </transition>
        </div>
      </form>
    </div>
  </div>
</template>
<script>
import post from "../functions/post.js";
import delay from "../functions/delay.js";
import builder from "../store/builder.js";
import formHeading from "../components/FormHeading";
import primaryButton from "../components/PrimiaryButton";
import textField from "../components/TextField";
import plusIcon from "../components/PlusIcon";
import baseButton from "../components/BaseButton";
import poemCard from "../components/PoemCard";
import spinner from "../components/Spinner";
import checkIcon from "../components/CheckIcon";
import refreshIcon from "../components/RefreshIcon";
import deleteIcon from "../components/TrashIcon";
import caps from "../components/Caps";
import csrfField from "../components/CsrfField";
import responses from "../store/responses";

import clickable from "./BaseButton";
import __ from "../functions/translate";
import calculateLeft from "../mixins/calculateLeft";

export default {
  components: {
    clickable,
    formHeading,
    primaryButton,
    textField,
    baseButton,
    plusIcon,
    poemCard,
    caps,
    spinner,
    checkIcon,
    csrfField,
    refreshIcon,
    deleteIcon
  },
  props: {
    open: Boolean
  },
  data() {
    return {
      canClickOutside: false,
      selectedIds: [],
      state: "default",
      sendMeMyPoem: false
    };
  },
  mixins: [calculateLeft],
  methods: {
    __,
    ...builder.mapMutations([
      "resetBuilder",
      "setVideoUrl",
      "setThumbnailUrl",
      "setName",
      "setTitle",
      "setPhone"
    ]),

    ...responses.mapMutations(["addResponse"]),

    handleClickOutside() {
      this.$nextTick(() => {
        if (this.canClickOutside) {
          this.$emit("close");
        }
      });
    },

    handleNameChange(val) {
      this.setName(val);
    },

    handlePhoneChange(val) {
      this.setPhone(val);
    },

    handleTitleChange(val) {
      this.setTitle(val);
    },

    deleteVideo() {
      this.setVideoUrl(undefined);
      this.setThumbnailUrl(undefined);
    },

    handleTitleNext() {
      this.$refs.authorField.focus();
    },

    handleTitleFocus() {
      this.$refs.authorField.blur();
    },

    async handleSubmit() {
      this.state = "loading";

      await this.$parent.updateBlob();

      let blob = this.blob;

      let { data } = await post(
        window.location.pathname + "/responses",
        new FormData(this.$refs.form)
      );

      this.addResponse(data);

      this.state = "saved";

      this.$emit("publish", data);

      await delay(800);

      this.resetBuilder();
    }
  },
  watch: {
    open() {
      setTimeout(() => {
        this.canClickOutside = this.open;
      }, 300);
    }
  },
  computed: {
    ...builder.mapState([
      "words",
      "videoUrl",
      "thumbnailUrl",
      "title",
      "name",
      "phone",
      "blob"
    ]),

    ...builder.mapGetters(["wordsToSubmit"]),

    containerStyle({ open }) {
      return {
        transform: `translateY(${open ? "0" : "100"}%)`
      };
    },

    loading({ state }) {
      return state === "loading";
    },
    saved({ state }) {
      return state === "saved";
    },
    defaultState({ state }) {
      return state === "default";
    }
  }
};
</script>
