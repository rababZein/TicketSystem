<template>
  <div class>
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">Tickets Table</h3>

        <div class="card-tools">
          <button type="submit" class="btn btn-success btn-sm" @click="newModal">
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
              <th width="10%">Ticket#</th>
              <th width="45%">Name</th>
              <!-- <th width="30%">Description</th> -->
              <th width="20%">Client</th>
              <th width="10%">Project</th>
              <th width="10%">Read</th>
              <th width="10%">action</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="ticket in activeTickets" :key="ticket.id">
              <td>{{ ticket.number }}</td>
              <td>
                <router-link :to="'/ticket/' + ticket.id">{{ ticket.name }}</router-link>
              </td>
              <!-- <td v-trim="4">{{ ticket.description }}</td> -->
              <td>
                <span v-if="ticket.project">
                  <router-link
                    :to="'/profile/' + ticket.project.owner.id"
                  >{{ ticket.project.owner.name }}</router-link>
                </span>
              </td>
              <td>
                <span v-if="ticket.project">
                  <router-link :to="'/project/' + ticket.project.id">{{ ticket.project.name }}</router-link>
                </span>
              </td>
              <td v-if="!ticket.read">Not Read</td>
              <td v-else>Read</td>
              <td>
                <a href="#" @click="editModal(ticket)" class="btn btn-primary btn-xs">
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
                <quill-editor
                  v-model="form.description"
                  ref="myQuillEditor"
                  :options="editorOption"
                ></quill-editor>
                <has-error :form="form" field="description"></has-error>
              </div>
              <div class="form-group">
                <label for="name">Status</label>
                <multiselect
                  v-model="form.status"
                  :options="status"
                  placeholder="Select one"
                  label="name"
                  track-by="name"
                  @input="opt => form.status_id = opt.id"
                ></multiselect>

                <has-error :form="form" field="status_id"></has-error>
              </div>
              <div class="form-group" v-if="!isDisabled">
                <label for="name">Client</label>
                <multiselect
                  v-model="form.owner"
                  :options="owners"
                  @input="getProjectsByOwner(form.owner.id)"
                  :close-on-select="true"
                  :clear-on-select="false"
                  :preserve-search="true"
                  :allow-empty="false"
                  label="name"
                  deselect-label="Can't remove this value"
                  placeholder="Select one"
                ></multiselect>
                <has-error :form="form" field="client_id"></has-error>
              </div>
              <div class="form-group" v-if="form.owner">
                <label for="name">Project</label>
                <multiselect
                  v-model="form.project"
                  :options="projects"
                  :close-on-select="true"
                  :clear-on-select="false"
                  :preserve-search="true"
                  placeholder="Select one"
                  label="name"
                  @input="opt => form.project_id = opt.id"
                  :disabled="isDisabled"
                ></multiselect>
                <has-error :form="form" field="project_id"></has-error>
              </div>
            </div>

            <div class="modal-footer">
              <button v-show="!editMode" type="submit" class="btn btn-primary" :disabled="form.project_id == ''">Save</button>
              <button v-show="editMode" type="submit" class="btn btn-success" :disabled="form.project_id == ''">Update</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { quillEditor } from "vue-quill-editor";
import { mapGetters, mapState } from "vuex";
// require styles
import 'quill/dist/quill.core.css'
import 'quill/dist/quill.snow.css'
import 'quill/dist/quill.bubble.css'

export default {
  data() {
    return {
      editMode: false,
      isDisabled: false,
      form: new Form({
        id: "",
        name: "",
        description: null,
        owner: "",
        project: {
          id: "",
          name: ""
        },
        project_id: ""
      }),
      editorOption: {
        modules: {
          toolbar: [
            ["bold", "italic", "underline", "strike"],
            ["blockquote", "code-block"],
            [{ list: "ordered" }, { list: "bullet" }]
          ]
        }
      }
    };
  },
  components: {
    quillEditor
  },
  props: {
    tickets: {
      type: Object,
      required: true
    },
    singlePage: false
  },
  computed: {
    ...mapGetters({
      owners: "owner/activeOwners",
      projects: "project/projectByOwners",
      status: "ticket/activeStatus",
    }),
    ...mapGetters("project", {
      project: "activeSingleProject",
      ownerOfProject: "ownerOfProject"
    }),
    activeTickets() {
      return this.tickets.data;
    }
  },
  mounted() {
    this.getOwners();
    this.getStatus();
  },
  methods: {
    newModal() {
      this.editMode = false;
      this.form.reset();
      this.form.clear();
      if (this.singlePage) {
        this.form.project = this.project;
        this.form.project_id = this.project.id;
        this.isDisabled = true;
      }
      $("#Modal").modal("show");
    },
    editModal(ticket) {
      this.editMode = true;
      this.form.reset();
      this.form.clear();
      $("#Modal").modal("show");
      this.form.fill(ticket);
      this.form.owner = this.form.project.owner;
      if (this.singlePage) {
        this.form.project = this.project;
        this.form.project_id = this.project.id;
        this.isDisabled = false;
      }
    },

    getProjectsByOwner(ownerId) {
      this.form.project = [];
      if (ownerId !== null && ownerId !== "") {
        this.$store
          .dispatch("project/getProjectsByOwner", ownerId)
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
          // this.getTickets();
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
              Toast.fire({
                type: "success",
                title: response.data.message
              });
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
    },
    getOwners() {
      this.$store
        .dispatch("owner/getOwners")
        .then(() => {})
        .catch(error => {
          console.log(error);
        });
    },
    getStatus() {
      this.$store
        .dispatch("ticket/getStatus")
        .then(response => {})
        .catch(error => {
          console.log(error);
        });
    },
  },
  directives: {
    trim: {
      inserted: function(el, maxWords = 4) {
        var str = el.innerHTML;
        var resultString =
          str
            .split(" ")
            .slice(0, maxWords.value)
            .join(" ") + "...";
        el.innerHTML = resultString;
      }
    }
  }
};
</script>