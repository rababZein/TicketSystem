<template>
  <div class>
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
              <th>ID</th>
              <th width="10%">Name</th>
              <th width="30%">Description</th>
              <th width="20%">Client</th>
              <th width="10%">Project</th>
              <th width="10%">Read</th>
              <th width="10%">action</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="ticket in tickets.data" :key="ticket.id">
              <td>{{ ticket.id }}</td>
              <td>
                <router-link :to="'/ticket/' + ticket.id">{{ ticket.name }}</router-link>
              </td>
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
          @pagination-change-page="getTickets"
        ></pagination>
      </div>
      <!-- /.card-body -->
    </div>
    <!-- /.card -->
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
            <h5 v-show="!editMode" class="modal-title" id="newTicketLabel">Create New Ticket</h5>
            <h5 v-show="editMode" class="modal-title" id="newTicketLabel">Edit Ticket</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <form
            @submit.prevent="editMode ? editTicket(form) : createTicket(form)"
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
                  v-model="form.owner"
                  :options="owners"
                  @input="getProjectsByOwner(form.owner.id)"
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
                  @input="opt => form.project_id = opt.id"
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
import { mapGetters } from "vuex";

export default {
  data() {
    return {
      editMode: false,
      form: new Form({
        id: "",
        name: "",
        description: "",
        owner: "",
        project: {
          id: "",
          name: ""
        }
      })
    };
  },
  props: {
    tickets: {
      type: Object,
      required: true
    }
  },
  computed: {
    ...mapGetters("ticket", {
      owners: "ticketsOwners",
      projects: "projectByOwners"
    })
  },
  mounted() {
    this.getTickets();
    this.getOwners();
  },
  methods: {
    newModel() {
      this.editMode = false;
      this.form.reset();
      this.form.clear();
      $("#Modal").modal("show");
    },
    editModel(ticket) {
      this.editMode = true;
      this.form.reset();
      $("#Modal").modal("show");
      this.form.fill(ticket);
    },
    getTickets(page = 1) {
      this.$Progress.start();
      this.$store
        .dispatch("ticket/getTickets", page)
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
        .dispatch("ticket/getOwners")
        .then(() => {
          this.$Progress.finish();
        })
        .catch(error => {
          this.$Progress.fail();
        });
    },
    getProjectsByOwner(ownerId) {
      this.form.project = [];
      if (ownerId !== null && ownerId !== "") {
        this.$store
          .dispatch("ticket/getProjectsByOwner", ownerId)
          .then(() => {
            this.$Progress.finish();
          })
          .catch(error => {
            this.$Progress.fail();
          });
      }
    },
    createTicket(data) {
      this.$Progress.start();
      this.$store
        .dispatch("ticket/createTicket", data)
        .then(response => {
          $("#Modal").modal("hide");
          this.$Progress.finish();
          this.getTickets();
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

    editTicket(data) {
      this.$Progress.start();
      this.$store
        .dispatch("ticket/editTicket", data)
        .then(response => {
          $("#Modal").modal("hide");
          this.$Progress.finish();
          Toast.fire({
            type: "success",
            title: response.data.message
          });
        })
        .catch(error => {
          console.log(error);
          this.$Progress.fail();
          if (error.response) {
            this.form.errors.errors = error.response.data.errors;
          }
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
          this.$store
            .dispatch("ticket/deleteTicket", id)
            .then(response => {
              this.$Progress.finish();
              Swal.fire("Deleted!", response.data.message, "success");
              this.$store.dispatch("ticket/getTickets");
            })
            .catch(error => {
              console.log(error);
              this.$Progress.fail();
              Toast.fire({
                type: "error",
                title: error.response.data.message
              });
            });
        }
      });
    }
  }
};
</script>