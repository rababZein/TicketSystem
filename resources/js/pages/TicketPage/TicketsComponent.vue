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
      :limit="3"
      @pagination-change-page="onPaginate"
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
    onPaginate(page) {
      this.$router.push({
        name: "tickets.list",
        params: { page }
      });
    },
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
    }
  },
  mounted() {
    this.getTickets(this.$route.params.page || 1);
  },
  beforeRouteUpdate(to, from, next) {
    this.getTickets(to.params.page);
    next();
  },
  computed: {
    ...mapGetters("ticket", {
      tickets: "activeTickets"
    })
  }
};
</script>

