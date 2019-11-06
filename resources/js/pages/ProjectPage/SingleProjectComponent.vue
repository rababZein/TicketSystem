<template>
  <div class="row justify-content-center">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <span>Title:</span>
          <span class="font-weight-light">{{ project.name }}</span>
          <div class="float-right font-weight-light">{{ project.created_at | myDate }}</div>
        </div>

        <div class="card-body">
          <div class="row">
            <div class="col-sm-6">
              <div class="form-group">
                <label for="Description" class="col-form-label">Description:</label>
                <textarea
                  v-model="project.description"
                  class="form-control"
                  id="Description"
                  disabled
                ></textarea>
              </div>
            </div>
            <div class="col-sm-6">
              <table
                class="table table-borderless table-sm table-hover table-responsive-lg mt-2"
                style="width: 70%"
              >
                <tbody>
                  <tr>
                    <td>
                      <small>Tickets:</small>
                    </td>
                    <td>{{ tickets.total }}</td>
                  </tr>
                  <tr>
                    <td>
                      <small>Budget hours:</small>
                    </td>
                    <td>{{ project.budget_hours }}</td>
                  </tr>
                  <tr>
                    <td>
                      <small>Task rate:</small>
                    </td>
                    <td>{{ project.task_rate }}</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-12">
      <ticket-list v-if="showTickets" :tickets="tickets" :singlePage="true"></ticket-list>
    </div>
    <pagination
      align="center"
      size="small"
      :show-disabled="true"
      :data="tickets"
      :limit="3"
      @pagination-change-page="getTicketsByProjectId"
    ></pagination>
  </div>
</template>

<script>
import { mapGetters } from "vuex";

export default {
  data() {
    return {
      projectId: this.$route.params.id,
      showTickets: false
    };
  },
  methods: {
    getPrjectById(id) {
      this.$Progress.start();
      this.$store
        .dispatch("project/getProjectById", id)
        .then(response => {
          this.$Progress.finish();
        })
        .catch(error => {
          this.$Progress.fail();
        });
    },
    getTicketsByProjectId(page = 1) {
      this.$Progress.start();
      this.$store
        .dispatch("ticket/getTicketsByProjectId", {id: this.projectId, page: page})
        .then(response => {
          this.showTickets = true;
          this.$Progress.finish();
        })
        .catch(error => {
          this.$Progress.fail();
        });
    },

  },
  mounted() {
    this.getPrjectById(this.projectId);
    this.getTicketsByProjectId();
  },
  computed: {
    ...mapGetters({
      project: "project/activeSingleProject",
      tickets: "ticket/activeTickets"
    })
  }
};
</script>
