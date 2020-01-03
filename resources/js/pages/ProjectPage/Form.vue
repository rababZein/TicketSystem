<template>
  <div class="row justify-content-center" v-if="!loading">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <div class="card-title font-weight-light">Edit Project</div>
          <div class="card-tools">
          </div>
        </div>
        <form @submit.prevent="editProject()" @keydown="form.onKeydown($event)">
          <div class="card-body">
            <div class="row">
              <div class="form-group col-sm-12 col-md-3">
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
              <div class="form-group col-sm-12 col-md-3">
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
              <div class="form-group col-sm-12 col-md-3">
                <label for="name">Assigned Users</label>
                <multiselect
                  v-model="form.project_assign"
                  :options="responsible"
                  :multiple="true"
                  :close-on-select="false"
                  :clear-on-select="false"
                  :preserve-search="true"
                  placeholder="Pick some"
                  label="name"
                  track-by="id"
                  :preselect-first="true"
                >
                  <template slot="selection" slot-scope="{ values, search, isOpen }">
                    <span
                      class="multiselect__single"
                      v-if="values.length &amp;&amp; !isOpen"
                    >{{ values.length }} options selected</span>
                  </template>
                </multiselect>
                <has-error :form="form" field="project_assign"></has-error>
              </div>
              <div class="form-group col-sm-12 col-md-3">
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
              <div class="form-group col-sm-12 col-md-3">
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
              <div class="form-group col-sm-12 col-md-12">
                <label for="description">description</label>
                <quill-editor
                  id="comments-editor"
                  v-model="form.description"
                  ref="myQuillEditor"
                  :options="editorOption"
                ></quill-editor>
                <has-error :form="form" field="description"></has-error>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-success">Update</button>
          </div>
        </form>
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
  components: {
    quillEditor
  },
  data() {
    return {
      loading: true,
      projectId: this.$route.params.id,
      form: new Form({
        id: "",
        name: "",
        owner: "",
        description: "",
        task_rate: "",
        budget_hours: "",
        project_assign: []
      }),
      owner_id: "",
      selected: null,
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
  methods: {
    ...mapActions("project", ["getProjectById"]),
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
    getResponsibles() {
      this.$store
        .dispatch("regularUser/getRegularUser")
        .then()
        .catch(error => {
          console.log(error);
        });
    },
    async loadEditData() {
      this.$Progress.start();
      let response = await this.getProjectById(this.projectId)
        .then(() => {
          this.$Progress.finish();
          this.form.fill(this.singleProject);
          this.form.selected = _.map(this.form.project_assign, function(
            value,
            key
          ) {
            return value.name;
          });
          this.loading = false;
        })
        .catch(error => {
          this.$Progress.fail();
        });
    },
    editProject() {
      this.$Progress.start();
      // get user id only form assigned users
      this.form.project_assign.forEach(element => {
        this.form.project_assign = this.form.project_assign.filter(function(
          obj
        ) {
          return obj.id !== element.id;
        });
        this.form.project_assign.push(element.id);
      });
      this.$store
        .dispatch("project/editProject", this.form)
        .then(response => {
          $("#Modal").modal("hide");
          this.$Progress.finish();
          Toast.fire({
            type: "success",
            title: response.data.message
          });
          this.$router.push({
            name: "project",
            params: { id: this.projectId }
          });
        })
        .catch(error => {
          this.$Progress.fail();
          if (error.response) {
            this.form.errors.errors = error.response.data.errors;
          }
        });
    }
  },
  mounted() {
    this.getOwners();
    this.getResponsibles();
    this.loadEditData();
  },
  computed: {
    ...mapGetters({
      singleProject: "project/activeSingleProject",
      owners: "owner/activeOwners",
      responsible: "regularUser/activeRegularUser"
    })
  }
};
</script>

<style>
</style>