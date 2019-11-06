<template>
  <div class="row">
    <div class="col-12 mb-3">
      <div class="card card-default">
        <div class="card-header">
          <div class="card-tools">
            <div class="input-group input-group-sm" style="width: 150px;">
              <input
                type="text"
                name="table_search"
                class="form-control float-right"
                placeholder="Search"
              />

              <div class="input-group-append">
                <button type="submit" class="btn btn-default">
                  <i class="fas fa-search"></i>
                </button>
              </div>
            </div>
          </div>
        </div>
        <div class="card-body">
          <div class="container">
            <div class="row">
              <div
                v-for="project in projects.data"
                :key="project.id"
                v-show="project"
                class="col-lg-3 col-6"
              >
                <!-- small card -->
                <div class="small-box bg-blue">
                  <div class="inner">
                    <h3>{{ project.name }}</h3>

                    <p>{{ project.owner.name }}</p>
                    <a href="#" @click="editModal(project)" class="btn btn-light btn-xs">
                      <i class="fas fa-edit fa-fw"></i>
                    </a>
                    <a href="#" @click="deleteProject(project.id)" class="btn btn-xs btn-light">
                      <i class="fas fa-trash fa-fw"></i>
                    </a>
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
                <button
                  @click="newModal"
                  class="btn btn-dark btn-block"
                  style="display: block; height:90%;"
                >
                  <i class="fas fa-plus fa-2x"></i>
                  <p>create new project</p>
                </button>
              </div>
            </div>
          </div>
        </div>
        <div class="card-footer">
          <div class="col-12">
            <pagination
              v-if="projects.data"
              align="right"
              size="small"
              :show-disabled="true"
              :data="projects"
              :limit="3"
              @pagination-change-page="getProjects"
            ></pagination>
          </div>
        </div>
      </div>
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
          <form
            @submit.prevent="editMode ? editProject(form.id) : createProject()"
            @keydown="form.onKeydown($event)"
          >
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
                <label for="client">Client</label>
                <multiselect
                  v-model="form.owner"
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
                ></multiselect>
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
import { mapGetters, mapState, mapActions } from "vuex";

export default {
  data() {
    return {
      editMode: false,
      form: new Form({
        id: "",
        name: "",
        owner: "",
        description: "",
        task_rate: "",
        budget_hours: "",
        project_assign: []
      }),
      owner_id: ""
    };
  },
  methods: {
    getProjects(page = 1) {
      this.$Progress.start();
      this.$store
        .dispatch("project/getProjects", page)
        .then(() => {
          this.$Progress.finish();
        })
        .catch(error => {
          this.$Progress.fail();
        });
    },
    getOwners() {
      this.$Progress.start();
      this.$store
        .dispatch("owner/getOwners")
        .then(() => {
          this.$Progress.finish();
        })
        .catch(error => {
          this.$Progress.fail();
        });
    },
    newModal() {
      this.editMode = false;
      this.form.reset();
      this.form.clear();
      $("#Modal").modal("show");
    },
    editModal(item) {
      this.editMode = true;
      this.form.reset();
      this.form.clear();
      $("#Modal").modal("show");
      this.form.fill(item);
    },
    createProject() {
      this.$Progress.start();
      this.$store
        .dispatch("project/createProject", this.form)
        .then(response => {
          $("#Modal").modal("hide");
          this.$Progress.finish();
          Toast.fire({
            type: "success",
            title: response.data.message
          });
        })
        .catch(error => {
          this.$Progress.fail();
          if (error.response) {
            this.form.errors.errors = error.response.data.errors;
          }
        });
    },
    editProject(id) {
      this.$Progress.start();
      this.$store
        .dispatch("project/editProject", this.form)
        .then(response => {
          $("#Modal").modal("hide");
          this.$Progress.finish();
          this.getProjects();
          Toast.fire({
            type: "success",
            title: response.data.message
          });
        })
        .catch(error => {
          this.$Progress.fail();
          if (error.response) {
            this.form.errors.errors = error.response.data.errors;
          }
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
          this.$store
            .dispatch("project/deleteProject", id)
            .then(response => {
              this.$Progress.finish();
              this.getProjects();
              Swal.fire("Deleted!", response.data.message, "success");
            })
            .catch(error => {
              this.$Progress.fail();
              Toast.fire({
                type: "error",
                title: error.response.data.message
              });
            });
        }
      });
    }
  },
  mounted() {
    this.getProjects();
    this.getOwners();
  },
  computed: {
    ...mapGetters({
      projects: "project/activeProjects",
      owners: "owner/activeOwners"
    })
  }
};
</script>
