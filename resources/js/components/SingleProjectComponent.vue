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
            <div class="col-sm-6" v-if="project.tickets">
              <table
                class="table table-borderless table-sm table-hover table-responsive-lg mt-2"
                style="width: 70%"
              >
                <tbody>
                  <tr>
                    <td>
                      <small>Tickets:</small>
                    </td>
                    <td>{{ project.tickets.length }}</td>
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
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">
            Tickets in
            <strong>{{ project.name }}</strong>
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
                <th width="40%">Description</th>
                <th width="10%">Read</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="ticket in project.tickets" :key="ticket.id">
                <td>{{ ticket.id }}</td>
                <td>
                  <router-link :to="'/ticket/' + ticket.id">{{ ticket.name }}</router-link>
                </td>
                <td>{{ ticket.description }}</td>
                <td v-if="!ticket.read">Not Read</td>
                <td v-else>Read</td>
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
      projectId: this.$route.params.id,
      project: {}
    };
  },
  methods: {
    getPrject(project_id) {
      this.$api.projects
        .show(project_id)
        .then(response => {
          this.project = response.data.data;
          this.$Progress.finish();
        })
        .catch(error => {
          this.$Progress.fail();
        });
    }
  },
  mounted() {
    console.log("Component mounted.");
  },
  created() {
    this.getPrject(this.projectId);
  }
};
</script>
