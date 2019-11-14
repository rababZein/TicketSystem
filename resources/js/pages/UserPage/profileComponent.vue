<template>
  <div class="justify-content-center" v-if="!loading">
    <div class="row">
      <!-- card box -->
      <card-box cardBg="bg-info">
        <template v-slot:count>{{ projectCount }}</template>
        <template v-slot:title>projects</template>
        <template v-slot:icon>
          <i class="fas fa-briefcase"></i>
        </template>
      </card-box>
      <card-box cardBg="bg-success">
        <template v-slot:count>{{ ticketCount }}</template>
        <template v-slot:title>Tickets</template>
        <template v-slot:icon>
          <i class="fas fa-chart-pie"></i>
        </template>
      </card-box>
      <card-box cardBg="bg-warning">
        <template v-slot:count>{{ openTaskCount }}</template>
        <template v-slot:title>open Tasks</template>
        <template v-slot:icon>
          <i class="fas fa-tasks"></i>
        </template>
      </card-box>
      <card-box cardBg="bg-danger">
        <template v-slot:count>{{ closedTaskCount }}</template>
        <template v-slot:title>closed tasks</template>
        <template v-slot:icon>
          <i class="fas fa-list-ul"></i>
        </template>
      </card-box>
    </div>
    <div class="row">
      <!-- profile card -->
      <profile-card :user="user"></profile-card>
      <!-- /profile card -->
      <!-- activity list -->
      <tab-panel></tab-panel>
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
  mounted() {
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
      closedTaskCount: "user/ClosedTaskCountPerClient"
    })
  }
};
</script>