<template>
  <div class="row justify-content-center">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <span>Title:</span>
          <span class="font-weight-light">{{ ticket.name }}</span>
          <div class="float-right font-weight-light">{{ ticket.created_at | myDate }}</div>
        </div>

        <div class="card-body">
          <div class="row">
            <div class="col-sm-6">
              <div class="form-group">
                <label for="Description" class="col-form-label">Description:</label>
                <textarea
                  v-model="ticket.description"
                  class="form-control"
                  id="Description"
                  disabled
                ></textarea>
              </div>
            </div>
            <div class="col-sm-6" v-if="ticket.tasks">
              <table
                class="table table-borderless table-sm table-hover table-responsive-lg mt-4"
                style="width: 70%"
              >
                <tbody>
                  <tr>
                    <td>
                      <small>Tasks:</small>
                    </td>
                    <td>{{ ticket.tasks.length }}</td>
                  </tr>
                  <tr>
                    <td>
                      <small>Project:</small>
                    </td>
                    <td>{{ ticket.project.name }}</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- tasks for this ticket -->
    <div class="col-md-12">
      <task-list :tasks="tasks"></task-list>
    </div>
  </div>
</template>

<script>
import { mapGetters } from "vuex";

export default {
  data() {
    return {
      ticketId: this.$route.params.id
    };
  },
  methods: {
    getTicketById(id) {
      this.$Progress.start();
      this.$store
        .dispatch("ticket/getTicketById", id)
        .then(response => {
          this.$Progress.finish();
        })
        .catch(error => {
          this.$Progress.fail();
        });
    },
    getTasksByTicketId(page = 1) {
      this.$Progress.start();
      this.$store
        .dispatch("task/getTasksByTicketId", {id: this.ticketId, page: page})
        .then(response => {
          this.$Progress.finish();
        })
        .catch(error => {
          this.$Progress.fail();
        });
    }
  },
  mounted() {
    this.getTicketById(this.ticketId);
    this.getTasksByTicketId();
  },
  computed: {
    ...mapGetters({
      ticket: "ticket/activeTicket",
      tasks: "task/activeTasks"
    })
  }
};
</script>
