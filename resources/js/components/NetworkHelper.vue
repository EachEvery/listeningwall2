<template></template>

<script>
import getDownloadSpeed from "../functions/getDownloadSpeed";
import getUploadSpeed from "../functions/getUploadSpeed";

export default {
  data() {
    return {
      downloadSpeed: 0,
      uploadSpeed: 0
    };
  },
  watch: {
    downloadSpeed: function(val, old) {
      this.emitChange();
    },
    uploadSpeed: function(val, old) {
      this.emitChange();
    }
  },
  methods: {
    emitChange() {
      if (this.downloadSpeed === 0 || this.uploadSpeed === 0) {
        return;
      }

      this.$emit("connected", {
        downloadSpeed: this.downloadSpeed,
        uploadSpeed: this.uploadSpeed
      });
    },

    async poll() {
      clearTimeout(this.pollTimeout);

      this.scheduleError();

      this.downloadSpeed = await getDownloadSpeed();
      this.uploadSpeed = await getUploadSpeed();

      clearTimeout(this.errorTimeout);

      this.scheduleNext();
    },

    scheduleError() {
      clearTimeout(this.errorTimeout);

      this.errorTimeout = setTimeout(() => {
        this.$emit("error");
      }, 10 * 1000);
    },

    scheduleNext() {
      clearTimeout(this.pollTimeout);

      this.pollTimeout = setTimeout(() => {
        this.poll();
      }, 15 * 1000);
    }
  },
  mounted() {
    this.poll();
  }
};
</script>

