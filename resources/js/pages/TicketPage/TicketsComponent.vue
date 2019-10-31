<template>
  <div class="row justify-content-center">
    <div class="col-12">
      <ticket-list :tickets="tickets"></ticket-list>
    </div>
    <pagination
      align="center"
      size="small"
      :show-disabled="true"
      :data="tickets"
      @pagination-change-page="getTickets"
    ></pagination>
  </div>
</template>

<script>
import { mapGetters } from "vuex";

export default {
  data() {
    return {};
  },
  methods: {
    getTickets(page = 1) {
      this.$Progress.start();
      this.$store
        .dispatch("ticket/getTickets", page)
        .then(() => {
          this.$Progress.finish();
        })
        .catch(error => {
          this.$Progress.fail();
        });
    },
  },
  mounted() {
    this.getTickets();
  },
  computed: {
    ...mapGetters("ticket", {
      tickets: "activeTickets"
    })
  }
};
</script>

