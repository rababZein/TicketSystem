<template>
  <div class="row justify-content-center" v-if="!loading">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <span>Title:</span>
          <span class="font-weight-light">{{ ticket.name }}</span>
          <div class="float-right font-weight-light">{{ ticket.created_at | DateWithTime }}</div>
        </div>

        <div class="card-body">
          <div class="row">
            <div class="form-group col-sm-12 col-md-6">
              <label for="ticket number" class="col-form-label">ticket#:</label>
              <span class="font-weight-light">{{ ticket.number }}</span>
            </div>
            <div class="form-group col-sm-12 col-md-6">
              <label for="Description" class="col-form-label">status:</label>
              <span class="font-weight-light badge bg-primary">{{ ticket.status.name }}</span>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-12">
              <div class="form-group">
                <label for="Description" class="col-form-label">Description:</label>
                <div
                  class="overflow-auto p-3 mb-3 mb-md-0 mr-md-3 bg-light"
                  v-html="ticket.description"
                  style="min-height:100px; max-height: 600px;"
                ></div>
              </div>
              <div class="card-footer bg-white" v-if="ticket.files && ticket.files.length > 0">
                <ul class="mailbox-attachments d-flex align-items-stretch clearfix">
                  <li v-for="file in ticket.files" :key="file.id">
                    <div class="mailbox-attachment-info">
                      <a
                        target="_plank"
                        :href=" file.attachment_path | filePath"
                        class="mailbox-attachment-name"
                      >
                        <i class="fas fa-paperclip"></i> {{ file.attachment_path | fileName }}
                      </a>
                    </div>
                  </li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- tasks for this ticket -->
    <div class="col-md-12">
      <task-list :tasks="tasks" :singlePage="true"></task-list>
      <ticket-comment></ticket-comment>
    </div>
  </div>
  <div class="card" v-else>
    <div class="card-body justify-content-center">loading...</div>
  </div>
</template>

<script>
import { mapGetters } from "vuex";
import Axios from "axios";

export default {
  data() {
    return {
      ticketId: this.$route.params.id,
      loading: true
    };
  },
  methods: {
    getTicketById(id) {
      this.$Progress.start();
      this.$store
        .dispatch("ticket/getTicketById", id)
        .then(response => {
          this.$Progress.finish();
          this.loading = false;
        })
        .catch(error => {
          this.$Progress.fail();
        });
    },
    getTasksByTicketId(page = 1) {
      this.$Progress.start();
      this.$store
        .dispatch("task/getTasksByTicketId", { id: this.ticketId, page: page })
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
  },
  filters: {
    filePath(path) {
      let str = path;
      let n = str.indexOf("storage");
      return "/storage/attachments" + str.substring(n + 7);
    },
    fileName(path) {
      let str = path;
      let n = str.lastIndexOf("/");
      return str.substring(n + 1);
    }
  }
};
</script>
