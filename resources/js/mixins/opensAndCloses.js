export default (openY = "20%", closedY = "100%", initOpen = false) => {
    return {
        data() {
            return {
                open: initOpen
            };
        },

        methods: {
            toggle() {
                this.open = !this.open;
            }
        },

        computed: {
            containerStyle({ open }) {
                return {
                    transform: `translateY(${open ? closedY : openY})`
                };
            }
        }
    };
};
