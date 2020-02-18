<template>
  <div
    :class="containerClasses"
    class="flex-col flex self-stretch transition relative"
    v-click-outside="handleClickOutside"
  >
    <input
      @focus="() => setState('focus')"
      @keyup="handleKeydown"
      class="bg-transparent border-b py-1 self-stretch text-xl focus:outline-none transition px-0 font-medium"
      :class="inputClass"
      :placeholder="__(placeholder)"
      :required="required"
      :type="type"
      v-model="value"
      :disabled="disabled"
      :maxlength="20"
      ref="input"
    />

    <input type="hidden" :name="name" :value="value" />

    <caps
      class="text-xs font-semibold mt-2 transition"
      :class="labelClass"
      :style="labelStyle"
    >{{ __(label) }}</caps>

    <transition name="fadeUp">
      <keyboard
        :class="{ dialpad: keyboardType === 'dialpad' }"
        v-if="focusing && !isNumber"
        v-model="value"
        :layouts="keyboardLayout"
        @done="handleDone"
        @next="handleNext"
        :maxlength="30"
      />
    </transition>
  </div>
</template>

<style lang="scss">
.vue-keyboard {
  position: absolute;
  left: 0;
  margin-left: 3rem;
  width: 100%;
  top: 90%;
  z-index: 100;
  margin-top: 0.5rem;
  padding-bottom: 2.5rem;
  width: 24rem;

  .vue-keyboard-row {
    display: flex;
    padding: 0;
    justify-content: center;

    .vue-keyboard-key {
      background: white;
      position: relative;

      &:focus {
        outline: none;
      }

      font-size: 1rem;
      min-width: 2.12rem;
      min-height: 2.12rem;
      margin: 0 !important;
      border-radius: 0;
      border: 0.006rem solid #ddd;
      padding: 0.2rem 0rem;
      color: rgb(41, 41, 41);
      transition: 300ms transform ease;

      &:focus {
        transform: scale(0.9);
      }

      &[data-action] {
        font-size: 0.5rem;
        flex-grow: 1;
      }

      &[data-action="space"] {
        height: 2rem;
      }
    }
  }

  &.dialpad {
    top: -0.75rem;
    left: 87%;

    .vue-keyboard-key[data-action] {
      flex-grow: 0 !important;
    }

    .vue-keyboard-row {
      justify-content: start;
    }
  }
}
</style>
<script>
import caps from "./Caps";
import keyboard from "vue-keyboard";
import __ from "../functions/translate";

export default {
  components: {
    caps,
    keyboard
  },
  props: {
    name: {
      type: String,
      required: true
    },
    label: {
      type: String,
      required: true
    },
    type: {
      default: "text"
    },
    keyboardType: {
      default: "text"
    },
    required: {
      default: true
    },
    placeholder: {
      default: ""
    },
    disabled: {
      default: false
    },
    inverted: {
      default: false
    },
    initialValue: {
      default: ""
    }
  },
  data() {
    return {
      state: "default",
      value: this.initialValue,
      canClickOutside: false
    };
  },
  methods: {
    __,

    handleClickOutside() {
      if (this.canClickOutside) {
        this.setState("default");
      }
    },

    focus() {
      console.log("focusing input: ", this.name);
      this.$refs.input.focus();
    },
    blur() {
      this.$refs.input.blur();
      this.state = "default";
    },
    handleNext() {
      this.state = "default";
      this.$emit("next");
    },
    setState(state) {
      console.log("setting state", state);
      this.$emit(state);
      this.state = state;
    },
    handleKeydown(e) {
      this.$emit("keyup", e);
    },
    handleDone() {
      this.state = "default";
    }
  },
  watch: {
    value: function() {
      this.$emit("change", this.value);
    },
    state() {
      clearTimeout(this.canClickOutside);

      this.canClickTimeout = setTimeout(() => {
        this.canClickOutside = this.focusing;
      }, 1000);
    }
  },
  computed: {
    keyboardLayout({ keyboardType }) {
      if (keyboardType === "dialpad") {
        return [`123|456|789|{←:backspace}0{✓:done}`];
      }

      return [
        `QWERTYUIOP{←:backspace}|{${__("CLEAR")}:clear}ASDFGHJKL{${__(
          "CLEAR"
        )}:clear}|{${__("DONE")}:done}ZXCVBNM{${__("NEXT")}:next}|{${__(
          "SPACE"
        )}:space}`
      ];
    },
    isNumber({ type }) {
      return type === "number";
    },
    isText({ type }) {
      return type === "text";
    },
    hovering({ state }) {
      return state === "hover";
    },
    focusing({ state }) {
      return state === "focus";
    },
    active({ hovering, focusing, disabled }) {
      return (hovering || focusing) && !disabled;
    },
    containerClasses({ active, disabled }) {
      return {
        "text-white": active,
        "text-white-60": !active,
        "opacity-50": disabled
      };
    },
    textarea({ type }) {
      return type === "textarea";
    },
    inputClass({ containerClasses, active, disabled }) {
      return {
        ...containerClasses,
        "border-white": active,
        "border-white-60": !active
      };
    },
    labelClass({ containerClasses }) {
      return {
        ...containerClasses
      };
    },
    labelStyle({ active }) {
      return {
        transform: active ? "translateY(.006rem) translateZ(0)" : "",
        "transform-origin": "center left"
      };
    }
  }
};
</script>
