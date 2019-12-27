<template>
  <div class="row justify-content-center" v-if="!loading">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <span>Title:</span>
          <span class="font-weight-light">{{ form.name }}</span>
          <div class="card-tools">
            <a href="#" @click="deleteTask(task.id)" class="btn btn-danger btn-xs">
              <i class="fas fa-trash fa-fw"></i>
            </a>
          </div>
        </div>

        <div class="card-body">
          <form @submit.prevent="editTask(form)" @keydown="form.onKeydown($event)">
            <div class="row">
              <div class="form-group col-sm-12 col-md-3">
                <label for="name">task Name</label>
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
                <label for="name">Client</label>
                <multiselect
                  v-model="form.project.owner"
                  :options="owners"
                  @input="setProjectByOwner(form.project.owner.id)"
                  :close-on-select="true"
                  :clear-on-select="false"
                  :preserve-search="true"
                  placeholder="Select one"
                  label="name"
                  :preselect-first="true"
                  :allow-empty="false"
                  deselect-label="Can't remove this value"
                ></multiselect>
                <has-error :form="form" field="client_id"></has-error>
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
                  :preselect-first="true"
                  :allow-empty="false"
                  deselect-label="Can't remove this value"
                  @input="opt => form.project_id = opt.id"
                ></multiselect>
                <has-error :form="form" field="project_id"></has-error>
              </div>
            </div>
            <div class="row">
              <div class="form-group col-sm-12 col-md-3" v-if="form.project.tickets">
                <label for="name">Ticket</label>
                <multiselect
                  v-model="form.ticket"
                  :options="form.project.tickets"
                  placeholder="Select one"
                  label="name"
                  @input="opt => form.ticket_id = opt.id"
                ></multiselect>
                <has-error :form="form" field="ticket_id"></has-error>
              </div>
              <div class="form-group col-sm-12 col-md-3">
                <label for="name">Responsible</label>
                <multiselect
                  v-model="form.responsible"
                  :options="responsible"
                  :close-on-select="true"
                  :clear-on-select="false"
                  :preserve-search="true"
                  placeholder="Select one"
                  label="name"
                  :preselect-first="true"
                  @input="opt => form.responsible_id = opt.id"
                >
                  <template slot="selection" slot-scope="{ values, search, isOpen }">
                    <span
                      class="multiselect__single"
                      v-if="values.length &amp;&amp; !isOpen"
                    >{{ values.length }} options selected</span>
                  </template>
                </multiselect>
                <has-error :form="form" field="responsible_id"></has-error>
              </div>
              <div class="form-group col-sm-12 col-md-3" v-if="form.priority">
                <label for="priority">priority</label>
                <multiselect
                  class="clearfix"
                  v-model="form.priority"
                  :options="priorityList"
                  :close-on-select="true"
                  :allow-empty="false"
                  :show-labels="false"
                  placeholder="Select one"
                ></multiselect>
                <has-error :form="form" field="priority"></has-error>
              </div>
            </div>
            <div class="row">
              <div class="form-group col-sm-12">
                <label for="description">Task Description</label>
                <quill-editor
                  id="comments-editor"
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
        description: "",
        status: {},
        project: {},
        project_id: "",
        ticket: [],
        ticket_id: "",
        responsible: {},
        responsible_id: "",
        priority: "",
        deadline: ""
      }),
      priorityList: ["normal", "high", "low"],
      editorOption: {
        modules: {
          toolbar: [
            ["bold", "italic", "underline", "strike"],
            ["blockquote", "code-block"],
            [{ list: "ordered" }, { list: "bullet" }]
          ]
        }
      },
      taskId: this.$route.params.id,
      loading: true
    };
  },
  methods: {
    ...mapActions({
      setTask: "task/getTaskById",
      setProjectByOwner: "project/getProjectsByOwner"
    }),
    editTask(data) {
      this.$Progress.start();
      this.$store
        .dispatch("task/editTask", data)
        .then(response => {
          $("#Modal").modal("hide");
          this.$Progress.finish();
          Toast.fire({
            type: "success",
            title: response.data.message
          });
          this.$router.push({ name: "task", params: { id: this.taskId } });
        })
        .catch(error => {
          this.$Progress.fail();
          if (error.response) {
            this.form.errors.errors = error.response.data.errors;
          }
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
          this.$store
            .dispatch("task/deleteTask", id)
            .then(response => {
              this.$Progress.finish();
              Toast.fire({
                type: "success",
                title: response.data.message
              });
              this.$router.push({ name: "tasks.list" });
            })
            .catch(error => {
              this.$Progress.fail();
              console.log(error);
              Toast.fire({
                type: "error",
                title: error.response.data.message
              });
            });
        }
      });
    },
    async loadEditData() {
      this.$Progress.start();
      let response = await this.setTask(this.taskId)
        .then(() => {
          this.$Progress.finish();
          this.loading = false;
          this.form.fill(this.task);
        })
        .catch(error => {
          this.$Progress.fail();
        });
      await this.setProjectByOwner(this.task.project.owner.id);
    },
    getStatus() {
      this.$store
        .dispatch("task/getStatus")
        .then(response => {})
        .catch(error => {
          console.log(error);
        });
    },
    getOwners() {
      this.$store
        .dispatch("owner/getOwners")
        .then(response => {})
        .catch(error => {
          console.log(error);
        });
    },
    getResponsibles() {
      this.$store
        .dispatch("regularUser/getRegularUser")
        .then()
        .catch(error => {
          console.log(error);
        });
    }
  },
  mounted() {
    this.getStatus();
    this.getOwners();
    this.getResponsibles();
    this.loadEditData();
  },
  computed: {
    ...mapGetters({
      task: "task/activeTask",
      status: "task/activeStatus",
      owners: "owner/activeOwners",
      projects: "project/projectByOwners",
      responsible: "regularUser/activeRegularUser"
    })
  },
  components: {
    quillEditor
  }
};
</script>
