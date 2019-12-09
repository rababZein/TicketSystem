<template>
  <div class="active tab-pane">
    <div class="card-body table-responsive p-0" style="height:330px;overflow-y:auto;">
      <table class="table table-hover">
        <thead>
          <tr>
            <th>ID</th>
            <th>ticket</th>
            <th>created at</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="ticket in tickets.data" :key="ticket.id">
            <td>{{ ticket.id }}</td>
            <td>
              <router-link :to="'/admin/ticket/' + ticket.id">{{ ticket.name }}</router-link>
            </td>
            <td>{{ ticket.created_at | DateWithTime }}</td>
          </tr>
        </tbody>
      </table>
    </div>
    <pagination
      class="mt-1"
      align="center"
      size="small"
      :show-disabled="true"
      :data="tickets"
      :limit="3"
      @pagination-change-page="getTicketsPerClient"
    ></pagination>
  </div>
  <!-- /.tab-pane -->
</template>

<script>
import { mapGetters } from "vuex";

export default {
  props: {
    userId: {
      type: Number,
      required: true
    }
  },
  methods: {
    getTicketsPerClient(page = 1) {
      this.$Progress.start();
      this.$store
        .dispatch("ticket/getTicketsPerClient", { id: this.userId, page: page })
        .then(() => {
          this.$Progress.finish();
        })
        .catch(error => {
          this.$Progress.fail();
        });
    }
  },
  mounted() {
    this.getTicketsPerClient();
  },
  computed: {
    ...mapGetters({
      tickets: "user/TicketsPerClient"
    })
  }
};
</script>

<style></style>
