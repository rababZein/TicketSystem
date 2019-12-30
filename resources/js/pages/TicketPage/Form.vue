<template>
  <div class="row justify-content-center" v-if="!loading">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <span>Title:</span>
          <span class="font-weight-light">{{ form.name }}</span>
          <div class="card-tools">
            <a href="#" @click="deleteTicket(ticketId)" class="btn btn-danger btn-xs">
              <i class="fas fa-trash fa-fw"></i>
            </a>
          </div>
        </div>

        <div class="card-body">
          <form @submit.prevent="editTicket(form)" @keydown="form.onKeydown($event)">
            <div class="row">
              <div class="form-group col-sm-12 col-md-3">
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

              <div class="form-group col-sm-12 col-md-3">
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
              <div class="form-group col-sm-12 col-md-3">
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
              <div class="form-group col-sm-12 col-md-3">
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
            </div>

            <div class="row">
              <div class="form-group col-sm-12">
                <label for="description">Ticket Description</label>
                <quill-editor
                  v-model="form.description"
                  ref="myQuillEditor"
                  :options="editorOption"
                ></quill-editor>
                <has-error :form="form" field="description"></has-error>
              </div>
            </div>
            <button type="submit" class="btn btn-success float-right">Update</button>
          </form>
        </div>
      </div>
    </div>
  </div>
  <div class="card" v-else>
    <div class="card-body justify-content-center">loading...</div>
  </div>
</template>

<script>
import { mapActions, mapGetters } from "vuex";
import { quillEditor } from "vue-quill-editor";

// require styles
import "quill/dist/quill.core.css";
import "quill/dist/quill.snow.css";
import "quill/dist/quill.bubble.css";

export default {
  data() {
    return {
      form: new Form({
        id: "",
        name: "",
        description: null,
        owner: "",
        status: "",
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
      ticketId: this.$route.params.id,
      loading: true
    };
  },
  methods: {
    ...mapActions("ticket", ["getTicketById"]),
    editTicket(data) {
      this.$Progress.start();
      this.$store
        .dispatch("ticket/editTicket", data)
        .then(response => {
          this.$Progress.finish();
          Toast.fire({
            type: "success",
            title: response.data.message
          });
          this.$router.push({ name: "ticket", params: { id: this.ticketId } });
        })
        .catch(error => {
          console.log(error);
          this.$Progress.fail();
          if (error.response) {
            this.form.errors.errors = error.response.data.errors;
          }
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
              this.$router.push({ name: "tickets.list" });
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
    async loadEditData() {
      this.$Progress.start();
      let response = await this.getTicketById(this.ticketId)
        .then(() => {
          this.$Progress.finish();
          this.loading = false;
          this.form.fill(this.ticket);
        })
        .catch(error => {
          this.$Progress.fail();
        });
    }
  },
  mounted() {
    this.getOwners();
    this.getStatus();
    this.loadEditData();
  },
  computed: {
    ...mapGetters({
      ticket: "ticket/activeTicket",
      owners: "owner/activeOwners",
      projects: "project/projectByOwners",
      status: "ticket/activeStatus"
    })
  },
  components: {
    quillEditor
  }
};
</script>
