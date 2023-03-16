<template>
  <div class="container">
    {{teste}}
  </div>
</template>

<script>
import { mapGetters } from 'vuex'

export default {
  mounted() {
    console.log("Component mounted.");
  },
  computed: {
    ...mapGetters(['teste']),
  }
};
</script>
