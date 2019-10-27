<template>
  <div class="row">
    <div class="col-12">
      <ticket-list :tickets="tickets"></ticket-list>
    </div>
  </div>
</template>

<script>
import { mapGetters } from "vuex";

export default {
  data() {
    return {};
  },
  methods: {},
  mounted() {
    // this.getProjects();
  },
  computed: {
    ...mapGetters("ticket", {
      tickets: "activeTickets"
    })
  }
};
</script>
<style scoped>
.invalid-feedback {
  display: inline;
}
</style>

