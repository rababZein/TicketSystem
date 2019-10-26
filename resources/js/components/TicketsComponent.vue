<template>
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">Tickets Table</h3>

          <div class="card-tools">
            <button type="submit" class="btn btn-success btn-sm" @click="newModel">
              <i class="fas fa-plus fa-fw"></i>
              <span class="d-none d-lg-inline">New Ticket</span>
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
                <th width="10%">Read</th>
                <th>action</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="ticket in tickets" :key="ticket.id">
                <td>{{ ticket.id }}</td>
                <td><router-link :to="'/ticket/' + ticket.id">{{ ticket.name }}</router-link></td>
                <td>{{ ticket.description }}</td>
                <td>{{ ticket.project.owner.name }}</td>
                <td>{{ ticket.project.name }}</td>
                <td v-if="!ticket.read">Not Read</td>
                <td v-else>Read</td>
                <td>
                  <a href="#" @click="editModel(ticket)" class="btn btn-primary btn-xs">
                    <i class="fas fa-edit fa-fw"></i>
                  </a>
                  <a href="#" @click="deleteTicket(ticket.id)" class="btn btn-danger btn-xs">
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
            :data="tickets"
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
      id="newTicket"
      tabindex="-1"
      role="dialog"
      aria-labelledby="newTicketLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 v-show="!editMode" class="modal-title" id="newTicketLabel">Create New Ticket</h5>
            <h5 v-show="editMode" class="modal-title" id="newTicketLabel">Edit Ticket</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <form
            @submit.prevent="editMode ? editTicket(form.id) : createTicket()"
            @keydown="form.onKeydown($event)"
          >
            <div class="modal-body">
              <div class="form-group">
                <label for="name">Ticket Name</label>
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
                <label for="description">Ticket Description</label>
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
                <has-error :form="form" field="client_id"></has-error>
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
                <has-error :form="form" field="project_id"></has-error>
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
        project_id: ""
      }),
      tickets: {},
      projects: [],
      owners: []
    };
  },
  methods: {
    newModel() {
      this.editMode = false;
      this.form.reset();
      $("#newTicket").modal("show");
    },
    editModel(ticket) {
      this.editMode = true;
      this.form.reset();
      $("#newTicket").modal("show");
      this.form.fill(ticket);
      this.getProjects(ticket.project.owner.id);

      this.form.selected = _.map(this.form.projects, function(value, key) {
        return value.name;
      });
    },
    getResults(page = 1) {
      this.$Progress.start();
      this.$api.tickets
        .get()
        .then(response => {
          this.tickets = response.data.data.data;
                   
          // convert array to object for paginate
          this.tickets = Object.assign({}, this.tickets);

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
    createTicket() {
      this.$Progress.start();
      // need to be enhance
      this.form.project_id = this.form.project.id;

      this.form
        .post("/v-api/tickets")
        .then(response => {
          $("#newTicket").modal("hide");
          this.$Progress.finish();
          this.getResults();
          Toast.fire({
            type: "success",
            title: "Ticket created successfully"
          });
        })
        .catch(error => {
          this.$Progress.fail();
          Toast.fire({
            type: "error",
            title: "can't create new Ticket"
          });
        });
    },
    editTicket(id) {
      this.$Progress.start();

      this.form.project_id = this.form.project.id;
      
      this.form
        .patch("/v-api/tickets/" + id)
        .then(response => {
          $("#newTicket").modal("hide");
          this.$Progress.finish();
          this.getResults();
          Toast.fire({
            type: "success",
            title: "Ticket updated successfully"
          });
        })
        .catch(error => {
          this.$Progress.fail();
          Toast.fire({
            type: "error",
            title: "can't update the ticket"
          });
        });
    },
    deleteTicket(id) {
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
          this.$api.tickets
            .delete(id)
            .then(response => {
              this.$Progress.finish();
              this.getResults();
              Swal.fire("Deleted!", "The ticket has been deleted.", "success");
            })
            .catch(error => {
              this.$Progress.fail();
              Toast.fire({
                type: "error",
                title: "can't delete the ticket"
              });
            });
        }
      });
    }
  },
  mounted() {
    this.getResults();
    this.getOwners();
    // this.getProjects();
    console.log(this.owners);
  }
};
</script>
<style scoped>
.invalid-feedback {
  display: inline;
}
</style>

