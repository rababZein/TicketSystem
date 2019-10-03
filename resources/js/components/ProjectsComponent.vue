<template>
  <div class="row">
    <div class="col-12 mb-3">
      <div class="input-group input-group-sm">
        <input
          class="form-control form-control-navbar"
          type="search"
          placeholder="Search"
          aria-label="Search"
        />
        <div class="input-group-append">
          <button class="btn btn-dark" type="submit">
            <i class="fas fa-search"></i>
          </button>
        </div>
      </div>
    </div>
    <div v-for="project in projects" :key="project.id" v-show="project" class="col-lg-3 col-6">
      <!-- small card -->
      <div class="small-box bg-blue">
        <div class="inner">
          <h3>{{ project.name }}</h3>

          <p>{{ project.name }}</p>
        </div>
        <div class="icon">
          <i class="fas fa-shopping-cart"></i>
        </div>
        <a href="#" class="small-box-footer">
          More info
          <i class="fas fa-arrow-circle-right"></i>
        </a>
      </div>
    </div>
    <div class="col-lg-3 col-6">
      <button @click="newModal" class="btn btn-dark btn-block" style="display: block; height:90%;">
        <i class="fas fa-plus fa-2x"></i>
        <p>create new project</p>
      </button>
    </div>
    <!-- Modal -->
    <div
      class="modal fade"
      id="newModal"
      tabindex="-1"
      role="dialog"
      aria-labelledby="newModalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="newModalLabel">Create New User</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <form @submit.prevent="createProject" @keydown="form.onKeydown($event)">
            <div class="modal-body">
              <div class="form-group">
                <label for="name">name</label>
                <input
                  v-model="form.name"
                  type="text"
                  name="name"
                  class="form-control"
                  :class="{ 'is-invalid': form.errors.has('name') }"
                />
                <has-error :form="form" field="name"></has-error>
              </div>
              <div class="form-group">
                <label for="description">description</label>
                <input
                  v-model="form.description"
                  type="text"
                  name="description"
                  class="form-control"
                  :class="{ 'is-invalid': form.errors.has('description') }"
                />
                <has-error :form="form" field="description"></has-error>
              </div>
              <div class="form-group">
                <label for="task_rate">task rate</label>
                <input
                  v-model="form.task_rate"
                  type="text"
                  name="task_rate"
                  class="form-control"
                  :class="{ 'is-invalid': form.errors.has('task_rate') }"
                />
                <has-error :form="form" field="task_rate"></has-error>
              </div>
              <div class="form-group">
                <label for="budget_hours">budget hours</label>
                <input
                  v-model="form.budget_hours"
                  type="text"
                  name="budget_hours"
                  class="form-control"
                  :class="{ 'is-invalid': form.errors.has('budget_hours') }"
                />
                <has-error :form="form" field="budget_hours"></has-error>
              </div>
            </div>

            <div class="modal-footer">
              <button type="submit" class="btn btn-primary">Save</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      projects: {},
      form: new Form({
        name: "",
        description: "",
        owner_id: "",
        task_rate: "",
        budget_hours: "",
        project_assign: []
      })
    };
  },
  methods: {
    getResults(page = 1) {
      this.$Progress.start();
      this.$api.projects
        .get({ page: page })
        .then(response => {
          this.projects = response.data.data;
          this.$Progress.finish();
        })
        .catch(error => {
          this.$Progress.fail();
        });
    },
    newModal() {
      $("#newModal").modal("show");
      this.form.reset();
    },
    createProject() {
      this.form
        .post("/projects")
        .then(response => {
          $("#newModal").modal("hide");
          this.$Progress.finish();
          this.getResults();
          Toast.fire({
            type: "success",
            title: response.data.message
          });
        })
        .catch(error => {
          this.$Progress.fail();
          Toast.fire({
            type: "error",
            title: error.response.data.message
          });
          this.form.errors.errors = error.response.data.data;
        });
    }
  },
  mounted() {
    this.getResults();
    console.log("Component mounted.");
  }
};
</script>
