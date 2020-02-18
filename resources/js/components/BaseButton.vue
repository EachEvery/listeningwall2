<template>
    <button
        class="bg-transparent focus:outline-none transition inline-block"
        :disabled="disabled"
        @click="handleClick"
    >
        <slot />
    </button>
</template>
<script>
export default {
    props: {
        disabled: {
            default: false
        }
    },
    methods: {
        handleClick(e) {
            this.$emit("click", e);
        }
    }
};
</script>
