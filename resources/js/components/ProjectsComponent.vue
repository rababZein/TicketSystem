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
          <a href="#" @click="editModal(project)" class="btn btn-light btn-xs"><i class="fas fa-edit fa-fw"></i></a>
          <a href="#" @click="deleteProject(project.id)" class="btn btn-xs btn-light"><i class="fas fa-trash fa-fw"></i></a>
        </div>
        <div class="icon">
          <i class="fas fa-briefcase"></i>
        </div>
        <router-link :to="'/project/' + project.id" class="small-box-footer">
          More info
          <i class="fas fa-arrow-circle-right"></i>
        </router-link>
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
      id="Modal"
      tabindex="-1"
      role="dialog"
      aria-labelledby="newModalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 v-show="!editMode" class="modal-title" id="newModalLabel">Create New Project</h5>
            <h5 v-show="editMode" class="modal-title" id="newModalLabel">edit Project</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <form @submit.prevent="editMode ? editProject(form.id) : createProject()" @keydown="form.onKeydown($event)">
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
                <textarea
                  v-model="form.description"
                  type="text"
                  name="description"
                  class="form-control"
                  :class="{ 'is-invalid': form.errors.has('description') }"
                ></textarea>
                <has-error :form="form" field="description"></has-error>
              </div>
              <div class="form-group">
                <label for="name">Client</label>
                <multiselect
                  v-model="owner_id"
                  :options="owners"
                  :searchable="true"
                  :close-on-select="true"
                  :clear-on-select="false"
                  :preserve-search="true"
                  placeholder="Select one"
                  label="name"
                  track-by="name"
                  :preselect-first="true"
                  @input="opt => form.owner_id = opt.id"
                >

                </multiselect>
                <has-error :form="form" field="owner_id"></has-error>
              </div>
              <div class="form-group">
                <label for="task_rate">task rate</label>
                <input
                  v-model="form.task_rate"
                  type="number"
                  min="0"
                  step="0.01"
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
                  type="number"
                  min="0"
                  step="0.01"
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
      editMode: false,
      projects: {},
      form: new Form({
        id: "",
        name: "",
        owner_id: "",
        description: "",
        task_rate: "",
        budget_hours: "",
        project_assign: []
      }),
      owners: [],
      owner_id: ""
    };
  },
  methods: {
    getResults(page = 1) {
      this.$Progress.start();
      this.$api.projects
        .get({ page: page })
        .then(response => {
          this.projects = response.data.data.data;
          this.$Progress.finish();
        })
        .catch(error => {
          this.$Progress.fail();
        });
    },
    getOwners() {
      this.$api.owners
        .getAll()
        .then(response => {
          this.owners = _.map(response.data.data, function(key, value) {
            return { id: key.id, name: key.name };
          });
          this.$Progress.finish();
        })
        .catch(error => {
          this.$Progress.fail();
        });
    },
    selectOwnerId(opt) {
      opt => form.owner_id = opt.id
    },
    newModal() {
      this.editMode = false;
      this.form.reset();
      $("#Modal").modal("show");
    },
    editModal(item) {
      this.editMode = true;
      this.form.reset();
      $("#Modal").modal("show");
      this.form.fill(item);
    },
    createProject() {
      this.form
        .post("/v-api/projects")
        .then(response => {
          $("#Modal").modal("hide");
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
    },
    editProject(id) {
      this.$Progress.start();
      this.form
        .put("/v-api/projects/" + id)
        .then(response => {
          $("#Modal").modal("hide");
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
        });
    },
    deleteProject(id) {
      Swal.fire({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, delete it!"
      }).then(result => {
        if (result.value) {
          this.$Progress.start();
          this.$api.projects
            .delete(id)
            .then(response => {
              this.$Progress.finish();
              this.getResults();
              Swal.fire("Deleted!", response.data.message, "success");
            })
            .catch(error => {
              this.$Progress.fail();
              Toast.fire({
                type: "error",
                title: "can't delete the project"
              });
            });
        }
      });
    }
  },
  mounted() {
    this.getResults();
    this.getOwners();
  }
};
</script>

<style scoped>
.invalid-feedback {
  display: inline;
}
</style>