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
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">
            Tickets in
            <strong>{{ ticket.name }}</strong>
          </h3>

          <div class="card-tools"></div>
        </div>
        <!-- /.card-header -->
        <div class="card-body table-responsive p-0">
          <table class="table table-hover">
            <thead>
              <tr>
                <th width="10">ID</th>
                <th width="40%">Name</th>
                <th width="50%">Description</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="task in ticket.tasks" :key="task.id">
                <td>{{ task.id }}</td>
                <td>
                  <router-link :to="'/task/' + task.id">{{ task.name }}</router-link>
                </td>
                <td>{{ task.description }}</td>
              </tr>
            </tbody>
          </table>
        </div>
        <div class="card-footer clear-fix">
          <!-- <pagination
            align="right"
            size="small"
            :show-disabled="true"
            :data="project.tickets"
            @pagination-change-page="getResults"
          ></pagination>-->
        </div>
        <!-- /.card-body -->
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      ticketId: this.$route.params.id,
      ticket: {}
    };
  },
  methods: {
    getPrject(id) {
      this.$Progress.start();
      this.$api.tickets
        .show(id)
        .then(response => {
          this.ticket = response.data.data;
          this.$Progress.finish();
        })
        .catch(error => {
          this.$Progress.fail();
        });
    }
  },
  created() {
    this.getPrject(this.ticketId);
  }
};
</script>
