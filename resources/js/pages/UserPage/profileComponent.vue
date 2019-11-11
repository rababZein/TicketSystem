<template>
  <div class="justify-content-center" v-if="!loading">
    <div class="row">
      <!-- card box -->
      <card-box :count="projectCount" title="Project" cardBg="bg-info" icon="fas fa-briefcase"></card-box>
      <card-box :count="ticketCount" title="Tickets" cardBg="bg-success" icon="fas fa-chart-pie"></card-box>
      <card-box :count="openTaskCount" title="open Tasks" cardBg="bg-warning" icon="fas fa-tasks"></card-box>
      <card-box :count="closedTaskCount" title="closed tasks" cardBg="bg-danger" icon="fas fa-list-ul"></card-box>
    </div>
    <div class="row">
      <!-- profile card -->
      <profile-card :user="user"></profile-card>
      <!-- /profile card -->
      <!-- activity list -->
      <activity-list></activity-list>
      <!-- /activity list -->
    </div>
  </div>
  <div class="card" v-else>
    <div class="card-body justify-content-center">loading...</div>
  </div>
</template>

<script>
import { mapGetters } from "vuex";

export default {
  data() {
    return {
      userId: this.$route.params.id,
      loading: true
    };
  },
  methods: {
    getUserById(id) {
      this.$Progress.start();
      this.$store
        .dispatch("user/getUserById", id)
        .then(response => {
          this.$Progress.finish();
        })
        .catch(error => {
          this.$Progress.fail();
        });
    },
    getProjectCountPerClient(id) {
      this.$Progress.start();
      this.$store
        .dispatch("project/getProjectCountPerClient", id)
        .then(response => {
          this.$Progress.finish();
        })
        .catch(error => {
          this.$Progress.fail();
        });
    },
    getTicketCountPerClient(id) {
      this.$Progress.start();
      this.$store
        .dispatch("ticket/getTicketCountPerClient", id)
        .then(response => {
          this.$Progress.finish();
        })
        .catch(error => {
          this.$Progress.fail();
        });
    },
    getTaskCountPerClient(id) {
      this.$Progress.start();
      this.$store
        .dispatch("task/getTaskCountPerClient", id)
        .then(response => {
          this.$Progress.finish();
          this.loading = false;
        })
        .catch(error => {
          this.$Progress.fail();
        });
    }
  },
  created() {
    this.getUserById(this.userId);
    this.getProjectCountPerClient(this.userId);
    this.getTicketCountPerClient(this.userId);
    this.getTaskCountPerClient(this.userId);
  },
  computed: {
    ...mapGetters({
      user: "user/activeSingleUser",
      projectCount: "user/ProjectCountPerClient",
      ticketCount: "user/TicketCountPerClient",
      openTaskCount: "user/OpenTaskCountPerClient",
      closedTaskCount: "user/closedTaskCountPerClient"
    })
  }
};
</script>