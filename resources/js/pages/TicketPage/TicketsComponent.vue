<template>
  <div class="row justify-content-center">
    <div class="col-12">
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
          <vue-bootstrap4-table
            v-if="resultTickets"
            :rows="resultTickets"
            :columns="columns"
            :config="config"
            @on-change-query="onChangeQuery"
            :total-rows="total_rows"
            :classes="classes"
            @on-download="onChangeQuery"
          >
            <template slot="sort-asc-icon">
              <i class="fas fa-sort-up"></i>
            </template>
            <template slot="sort-desc-icon">
              <i class="fas fa-sort-down"></i>
            </template>
            <template slot="no-sort-icon">
              <i class="fas fa-sort"></i>
            </template>
            <template slot="name" slot-scope="props">
              <router-link
                :to="{ name: 'ticket', params: { id: props.row.id }}"
              >{{ props.cell_value }}</router-link>
            </template>
            <template slot="created_at" slot-scope="props">
              {{ props.cell_value | DateOnly }}
            </template>
            <template slot="read" slot-scope="props">
              <p  v-if="!props.cell_value">Not Read</p>
              <p v-else>Read</p>
            </template>
            <template slot="action-buttons" slot-scope="props">
              <a href="#" @click="editModal(props.row)" class="btn btn-primary btn-xs">
                <i class="fas fa-edit fa-fw"></i>
              </a>
              <a href="#" @click="deleteTicket(props.row.id)" class="btn btn-danger btn-xs">
                <i class="fas fa-trash fa-fw"></i>
              </a>
            </template>
          </vue-bootstrap4-table>
        </div>
        <!-- /.card-body -->
      </div>
      <!-- /.card -->
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
                ></multiselect>
                <has-error :form="form" field="project_id"></has-error>
              </div>
            </div>

            <div class="modal-footer">
              <button
                v-show="!editMode"
                type="submit"
                class="btn btn-primary"
                :disabled="form.project_id == ''"
              >Save</button>
              <button
                v-show="editMode"
                type="submit"
                class="btn btn-success"
                :disabled="form.project_id == ''"
              >Update</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { mapGetters } from "vuex";
import VueBootstrap4Table from "vue-bootstrap4-table";
import moment from "moment";
import { quillEditor } from "vue-quill-editor";
import DatePicker from "vue2-datepicker";

// require styles
import "quill/dist/quill.core.css";
import "quill/dist/quill.snow.css";
import "quill/dist/quill.bubble.css";

export default {
  data() {
    return {
      editMode: false,
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
      },
      columns: [
        {
          label: "Ticket#",
          name: "number",
          filter: {
            type: "simple",
            placeholder: "Enter Ticket number"
          },
          sort: true,
          row_text_alignment: "text-left"
        },
        {
          label: "title",
          name: "name",
          filter: {
            type: "simple",
            placeholder: "Enter Ticket title"
          },
          sort: true,
          row_text_alignment: "text-left"
        },
        {
          label: "Status",
          name: "status.name",
          filter: {
            type: "simple",
            placeholder: "Enter status"
          },
          sort: true
        },
        {
          label: "Client",
          name: "project.owner.name",
          filter: {
            type: "simple",
            placeholder: "Enter Client"
          },
          sort: true
        },
        {
          label: "Project",
          name: "project.name",
          filter: {
            type: "simple",
            placeholder: "Enter Project"
          },
          sort: true
        },
        {
          label: "Created at",
          name: "created_at",
          sort: true
        },
        {
          label: "Read",
          name: "read",
          sort: true
        },
        {
          label: "action",
          name: "action-buttons"
        }
      ],
      config: {
        server_mode: true,
        card_mode: false,
        show_refresh_button: false,
        pagination: true,
        pagination_info: true,
        per_page: 15
      },
      classes: {
        table: {
          "table-sm": true
        }
      },
      queryParams: {
        sort: [],
        filters: [],
        global_search: "",
        per_page: 15,
        page: 1
      },
      total_rows: 0
    };
  },
  methods: {
    onChangeQuery(queryParams) {
      this.queryParams = queryParams;
      this.getTickets();
    },
    newModal() {
      this.editMode = false;
      this.form.reset();
      this.form.clear();
      if (this.singlePage) {
        this.form.project = this.project;
        this.form.project_id = this.project.id;
      }
      $("#Modal").modal("show");
    },
    editModal(ticket) {
      this.editMode = true;
      this.form.reset();
      this.form.clear();
      this.getProjectsByOwner(ticket.project.owner.id);
      $("#Modal").modal("show");
      this.form.fill(ticket);
      this.form.owner = this.form.project.owner;
      if (this.singlePage) {
        this.form.project = this.project;
        this.form.project_id = this.project.id;
      }
    },
    onPaginate(page) {
      this.$router.push({
        name: "tickets.list",
        params: { page }
      });
    },
    getTickets() {
      this.$Progress.start();
      this.$store
        .dispatch("ticket/getTickets", {
          queryParams: this.queryParams,
          page: this.queryParams.page
        })
        .then(response => {
          this.total_rows = response.data.data.total;
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
    }
  },
  mounted() {
    this.getTickets(this.$route.params.page || 1);
    this.getOwners();
    this.getStatus();
  },
  beforeRouteUpdate(to, from, next) {
    this.getTickets(to.params.page);
    next();
  },
  computed: {
    ...mapGetters({
      tickets: "ticket/activeTickets",
      owners: "owner/activeOwners",
      projects: "project/projectByOwners",
      status: "ticket/activeStatus"
    }),
    resultTickets() {
      return this.tickets.data;
    }
  },
  components: {
    VueBootstrap4Table,
    quillEditor,
    DatePicker
  }
};
</script>

