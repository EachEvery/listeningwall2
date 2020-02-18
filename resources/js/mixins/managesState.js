export default {
    data() {
        return { state: "default", lastState: "" };
    },

    methods: {
        setState(state) {
            this.state = state;
        },
        setStateChain(states, timeout = 800) {
            return new Promise(resolve => {
                states.forEach((state, i) => {
                    setTimeout(() => {
                        this.setState(state);

                        if (i === states.length - 1) {
                            resolve();
                        }
                    }, i * timeout);
                });
            });
        }
    },

    computed: {
        isDone({ state }) {
            return state === "done";
        },
        isEditing({ state }) {
            return state === "edit";
        },
        isLoading({ state }) {
            return state === "loading";
        },
        isOpen({ state }) {
            return state === "open";
        },
        isHovering({ state }) {
            return state === "hover";
        },
        isInvalid({ state }) {
            return state === "isInvalid";
        },
        showingForm({ state }) {
            return state === "showForm";
        }
    },

    watch: {
        state(val, oldVal) {
            this.lastState = oldVal;
        }
    }
};
