<template>
  <div>
    <input type="hidden" name="_token" :value="token">
    <input type="hidden" name="_method" :value="method" v-if="method !== ''">
  </div>
</template>

<script>
export default {
  props: {
    method: {
      type: String,
      default: ""
    }
  },
  data() {
    return {
      token: ""
    };
  },
  methods: {
    async fetchToken() {
      let response = await window.axios.get("/csrf");

      return Promise.resolve(response.data);
    },
    async refreshToken() {
      let token = await this.fetchToken();

      this.token = token;
    }
  },
  mounted() {
    this.interval = setInterval(() => {
      this.refreshToken();
    }, 1000 * 120);

    this.fetchToken();
  },
  destroyed() {
    window.clearInterval(this.interval);
  }
};
</script>
