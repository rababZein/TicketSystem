<template>
  <div class="board row justify-content-center" v-if="!loading">
    <template v-if="board.board.length < 1">
      <div class="col-sm-12">
        <div class="alert alert-secondary">there is no tasks in this project</div>
      </div>
    </template>
    <template v-else>
      <div class="col-sm-12">
        <div class="card">
          <div class="card-header">
            <span>project: {{ board.board.name }}</span>
            <div class="card-tools">
              <router-link
                :to="{ name: 'project', params: { id: this.$route.params.projectId }}"
                class="btn btn-default btn-sm"
              >
                <i class="fas fa-info fa-fw"></i> default view
              </router-link>
            </div>
          </div>
          <div class="card-body">
            <div class="row">
              <div
                v-for="(column, $columnIndex) of board.board.columns"
                :key="$columnIndex++"
                @drop="moveTask($event, $columnIndex)"
                @dragover.prevent
                @dragenter.prevent
                class="col-sm-12 col-md-3"
              >
                <div class="card bg-light">
                  <div class="card-header">{{ column.name }}</div>
                  <div
                    id="scrollbar-style"
                    class="card-body p-1 overflow-auto"
                    style="min-height: 100px; max-height: 300px;"
                  >
                    <div
                      class="bg-white p-1 m-1 border"
                      v-for="(task, $taskIndex) of column.tasks"
                      :key="$taskIndex"
                      draggable
                      @dragstart="pickupTask($event, task.id, $columnIndex)"
                      @click="editModal(task)"
                    >{{ task.name }}</div>
                  </div>
                  <div class="card-footer p-2">
                    <input
                      type="text"
                      class="block p-0 col-12 bg-transparent border-0"
                      placeholder="+ Enter new task"
                      @keyup.enter="createTask($event, $columnIndex)"
                    />
                  </div>
                </div>
              </div>
            </div>
          </div>
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
                <h5 class="modal-title" id="newTaskLabel">Edit Task</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <form @submit.prevent="editTask(form)" @keydown="form.onKeydown($event)">
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
                    <quill-editor
                      id="comments-editor"
                      v-model="form.description"
                      ref="myQuillEditor"
                      :options="editorOption"
                    ></quill-editor>
                    <has-error :form="form" field="description"></has-error>
                  </div>
                  <div class="form-group">
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
                    ></multiselect>
                    <has-error :form="form" field="responsible_id"></has-error>
                  </div>
                  <div class="form-group" v-if="form.priority">
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
                  <div class="form-group">
                    <label for="deadline">deadline</label>
                    <date-picker
                      v-model="form.deadline"
                      lang="en"
                      type="datetime"
                      format="YYYY-MM-DD HH:mm:ss"
                      :minute-step="15"
                      value-type="format"
                      input-class="form-control"
                    ></date-picker>
                    <has-error :form="form" field="deadline"></has-error>
                  </div>
                </div>

                <div class="modal-footer">
                  <button
                    type="submit"
                    class="btn btn-success"
                  >Update</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </template>
  </div>
  <div class="card" v-else>
    <div class="card-body justify-content-center">loading...</div>
  </div>
</template>

<script>
import { quillEditor } from "vue-quill-editor";
import DatePicker from "vue2-datepicker";
import { mapGetters, mapState } from "vuex";

// require styles
import "quill/dist/quill.core.css";
import "quill/dist/quill.snow.css";
import "quill/dist/quill.bubble.css";

export default {
  data() {
    return {
      loading: true,
      projectTitle: this.$route.params.pagetitle || "board",
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
      }
    };
  },
  methods: {
    getTasksForBoard(projectId) {
      this.$Progress.start();
      this.$store
        .dispatch("board/getTasksForBoard", projectId)
        .then(() => {
          this.$Progress.finish();
          this.loading = false;
        })
        .catch(error => {
          this.$Progress.fail();
          if (error.response) {
            this.form.errors.errors = error.response.data.errors;
          }
          this.loading = false;
        });
    },
    pickupTask(e, taskIndex, fromColumnIndex) {
      e.dataTransfer.effectAllowed = "move";
      e.dataTransfer.dropEffect = "move";
      e.dataTransfer.setData("task-id", taskIndex);
      e.dataTransfer.setData("from-column-index", fromColumnIndex);
    },
    moveTask(e, toColumnIndex) {
      const fromColumnIndex = e.dataTransfer.getData("from-column-index");
      const taskId = e.dataTransfer.getData("task-id");

      this.$store.dispatch("board/moveTask", {
        fromColumnIndex,
        toColumnIndex,
        taskId
      });
    },
    createTask(e, columnIndex) {
      const projectId = this.$route.params.projectId;
      this.$store.dispatch("board/createTask", {
        columnIndex,
        projectId,
        title: e.target.value
      });
      // clear the input
      e.target.value = "";
    },
    editModal(task) {
      this.form.reset();
      this.form.clear();
      this.form.fill(task);
      $("#newTask").modal("show");
    },
    editTask(data) {
      this.$Progress.start();
      this.$store
        .dispatch("board/editTask", data)
        .then(response => {
          $("#newTask").modal("hide");
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
    getResponsibles() {
      this.$store
        .dispatch("regularUser/getRegularUser")
        .then()
        .catch(error => {
          console.log(error);
        });
    }
  },
  computed: {
    ...mapState(["board"]),
    ...mapGetters({
      responsible: "regularUser/activeRegularUser"
    })
  },
  components: {
    quillEditor,
    DatePicker
  },
  mounted() {
    this.getTasksForBoard(this.$route.params.projectId);
    this.getResponsibles();
  }
};
</script>

<style scoped>
#scrollbar-style::-webkit-scrollbar-track {
  -webkit-box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.3);
  background-color: #f5f5f5;
}

#scrollbar-style::-webkit-scrollbar {
  width: 6px;
  background-color: #f5f5f5;
}

#scrollbar-style::-webkit-scrollbar-thumb {
  background-color: #3b3b3b;
}

.mx-datepicker {
  display: block;
  width: unset;
}
</style>