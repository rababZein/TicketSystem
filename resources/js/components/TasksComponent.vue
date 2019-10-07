<template>
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">Tasks Table</h3>

          <div class="card-tools">
            <button type="submit" class="btn btn-success btn-sm" @click="newModel">
              <i class="fas fa-plus fa-fw"></i>
              <span class="d-none d-lg-inline">New Task</span>
            </button>
          </div>
        </div>
        <!-- /.card-header -->
        <div class="card-body table-responsive p-0">
          <table class="table table-hover">
            <thead>
              <tr>
                <th width="10">ID</th>
                <th width="20%">Name</th>
                <th width="40%">Description</th>
                <th width="20%">Client</th>
                <th width="20%">Project</th>
                <th width="10%">Ticket</th>
                <th width="10%">Responsible</th>
                <th width="10%">Count Hours</th>
                <th>action</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="task in tasks" :key="task.id">
                <td>{{ task.id }}</td>
                <td><router-link :to="'/task/' + task.id">{{ task.name }}</router-link></td>
                <td>{{ task.description }}</td>
                <td>{{ task.project.owner.name }}</td>
                <td>{{ task.project.name }}</td>
                <td>{{ task.ticket.name }}</td>
                <td>{{ task.responsible.name }}</td>
                <td>{{ task.count_hours }}</td>
                <td>
                  <a href="#" @click="editModel(task)" class="btn btn-primary btn-xs">
                    <i class="fas fa-edit fa-fw"></i>
                  </a>
                  <a href="#" @click="deleteTask(task.id)" class="btn btn-danger btn-xs">
                    <i class="fas fa-trash fa-fw"></i>
                  </a>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
        <div class="card-footer clear-fix">
          <pagination
            align="right"
            size="small"
            :show-disabled="true"
            :data="tasks"
            @pagination-change-page="getResults"
          ></pagination>
        </div>
        <!-- /.card-body -->
      </div>
      <!-- /.card -->
    </div>
    <!-- Modal -->
    <div
      class="modal fade"
      id="newTask"
      tabindex="-1"
      role="dialog"
      aria-labelledby="newTaskLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 v-show="!editMode" class="modal-title" id="newTaskLabel">Create New Task</h5>
            <h5 v-show="editMode" class="modal-title" id="newTaskLabel">Edit Task</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <form
            @submit.prevent="editMode ? editTask(form.id) : createTask()"
            @keydown="form.onKeydown($event)"
          >
            <div class="modal-body">
              <div class="form-group">
                <label for="name">Task Name</label>
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
                <label for="description">Task Description</label>
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
                <label for="name">Client</label>
                <multiselect
                  v-model="form.project.owner"
                  :options="owners"
                  @input="getProjects(form.project.owner.id)"
                  :close-on-select="true"
                  :clear-on-select="false"
                  :preserve-search="true"
                  placeholder="Select one"
                  label="name"
                  track-by="name"
                  :preselect-first="true"
                >
                  <template slot="selection" slot-scope="{ values, search, isOpen }">
                    <span
                      class="multiselect__single"
                      v-if="values.length &amp;&amp; !isOpen"
                    >{{ values.length }} options selected</span>
                  </template>
                </multiselect>
                <has-error :form="form" field="name"></has-error>
              </div>
              <div class="form-group">
                <label for="name">Project</label>
                <multiselect
                  v-model="form.project"
                  :options="projects"
                  :close-on-select="true"
                  :clear-on-select="false"
                  :preserve-search="true"
                  placeholder="Select one"
                  label="name"
                  track-by="name"
                  :preselect-first="true"
                >
                  <template slot="selection" slot-scope="{ values, search, isOpen }">
                    <span
                      class="multiselect__single"
                      v-if="values.length &amp;&amp; !isOpen"
                    >{{ values.length }} options selected</span>
                  </template>
                </multiselect>
                <has-error :form="form" field="name"></has-error>
              </div>
              <div class="form-group">
                <label for="name">Ticket</label>
                <multiselect
                  v-model="form.ticket"
                  :options="tickets"
                  :close-on-select="true"
                  :clear-on-select="false"
                  :preserve-search="true"
                  placeholder="Select one"
                  label="name"
                  track-by="name"
                  :preselect-first="true"
                >
                  <template slot="selection" slot-scope="{ values, search, isOpen }">
                    <span
                      class="multiselect__single"
                      v-if="values.length &amp;&amp; !isOpen"
                    >{{ values.length }} options selected</span>
                  </template>
                </multiselect>
                <has-error :form="form" field="name"></has-error>
              </div>
              <div class="form-group">
                <label for="name">Responsible</label>
                <multiselect
                  v-model="form.responsible"
                  :options="responsibles"
                  :close-on-select="true"
                  :clear-on-select="false"
                  :preserve-search="true"
                  placeholder="Select one"
                  label="name"
                  track-by="name"
                  :preselect-first="true"
                >
                  <template slot="selection" slot-scope="{ values, search, isOpen }">
                    <span
                      class="multiselect__single"
                      v-if="values.length &amp;&amp; !isOpen"
                    >{{ values.length }} options selected</span>
                  </template>
                </multiselect>
                <has-error :form="form" field="name"></has-error>
              </div>
              <div class="form-group">
                <label for="name">Count Hours</label>
                <input
                  v-model="form.count_hours"
                  type="text"
                  name="count_hours"
                  class="form-control"
                  :class="{ 'is-invalid': form.errors.has('count_hours') }"
                />
                <has-error :form="form" field="count_hours"></has-error>
              </div>
            </div>

            <div class="modal-footer">
              <button v-show="!editMode" type="submit" class="btn btn-primary">Save</button>
              <button v-show="editMode" type="submit" class="btn btn-success">Update</button>
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
      form: new Form({
        id: "",
        name: "",
        description: "",
        project: {
          id: "",
          name: "",
          owner: ""
        },
        project_id: "",
        ticket: "",
        ticket_id: "",
        responsible: "",
        responsible_id: "",
        count_hours: ""
      }),
      tasks: {},
      responsibles: [],
      tickets: [],
      projects: [],
      owners: []
    };
  },
  methods: {
    newModel() {
      this.editMode = false;
      this.form.reset();
      $("#newTask").modal("show");
    },
    editModel(task) {
      this.editMode = true;
      this.form.reset();
      $("#newTask").modal("show");
      this.form.fill(task);
      this.getProjects(task.project.owner.id);

      this.form.selected = _.map(this.form.projects, function(value, key) {
        return value.name;
      });
    },
    getResults(page = 1) {
      this.$Progress.start();
      this.$api.tasks
        .getAll()
        .then(response => {
          this.tasks = response.data.data;
          
          // convert array to object for paginate
          this.tasks = Object.assign({}, this.tasks);

          this.$Progress.finish();
        })
        .catch(error => {
          this.$Progress.fail();
        });
    },
    getTickets() {
      this.$api.tickets
        .getAll()
        .then(response => {
          this.tickets = _.map(response.data.data, function(key, value) {
            return { id: key.id, name: key.name };
          });
          this.$Progress.finish();
        })
        .catch(error => {
          this.$Progress.fail();
        });
    },
    getResponsibles() {
      this.$api.responsibles
        .getAll()
        .then(response => {
          this.responsibles = _.map(response.data.data, function(key, value) {
            return { id: key.id, name: key.name };
          });
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
    getProjects(owner_id) {
      this.$api.projects
        .getAllByOwner(owner_id)
        .then(response => {
          this.projects = _.map(response.data.data, function(key, value) {
            return { id: key.id, name: key.name, owner:key.owner };
          });
          this.$Progress.finish();
        })
        .catch(error => {
          this.$Progress.fail();
        });
    },
    createTask() {
      this.$Progress.start();
      // need to be enhance
      this.form.project_id = this.form.project.id;
      this.form.ticket_id = this.form.ticket.id;
      this.form.responsible_id = this.form.responsible.id;

      this.form
        .post("/v-api/tasks")
        .then(response => {
          $("#newTask").modal("hide");
          this.$Progress.finish();
          this.getResults();
          Toast.fire({
            type: "success",
            title: "Task created successfully"
          });
        })
        .catch(error => {
          this.$Progress.fail();
          Toast.fire({
            type: "error",
            title: "can't create new Task"
          });
        });
    },
    editTask(id) {
      this.$Progress.start();
      // need to be enhance
      console.log('edit',this.form);
      this.form.project_id = this.form.project.id;
      this.form.ticket_id = this.form.ticket.id;
      this.form.responsible_id = this.form.responsible.id;
      
      this.form
        .patch("/v-api/tasks/" + id)
        .then(response => {
          $("#newTask").modal("hide");
          this.$Progress.finish();
          this.getResults();
          Toast.fire({
            type: "success",
            title: "Task updated successfully"
          });
        })
        .catch(error => {
          this.$Progress.fail();
          Toast.fire({
            type: "error",
            title: "can't update the task"
          });
        });
    },
    deleteTask(id) {
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
          this.$api.tasks
            .delete(id)
            .then(response => {
              this.$Progress.finish();
              this.getResults();
              Swal.fire("Deleted!", "The task has been deleted.", "success");
            })
            .catch(error => {
              this.$Progress.fail();
              Toast.fire({
                type: "error",
                title: "can't delete the task"
              });
            });
        }
      });
    }
  },
  mounted() {
    this.getResults();
    this.getOwners();
    this.getResponsibles();
    this.getTickets();
    console.log('xx',this.tickets);
  }
};
</script>

