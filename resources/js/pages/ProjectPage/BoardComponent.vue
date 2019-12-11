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
            <span>project \ {{ projectTitle }}</span>
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
                :key="$columnIndex"
                @drop="moveTask($event, column.tasks)"
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
                      @dragstart="pickupTask($event, $taskIndex, $columnIndex)"
                    >{{ task.name }}</div>
                  </div>
                </div>
              </div>
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
import { mapState } from "vuex";

export default {
  data() {
    return {
      loading: true,
      projectTitle: this.$route.params.pagetitle || "board"
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
      e.dataTransfer.setData("task-index", taskIndex);
      e.dataTransfer.setData("from-column-index", fromColumnIndex);
    },
    moveTask(e, toTasks) {
      const fromColumnIndex = e.dataTransfer.getData("from-column-index");
      const fromTasks = this.board.columns[fromColumnIndex].tasks;
      const taskIndex = e.dataTransfer.getData("task-index");

      this.$store.commit("board/MOVE_TASK", {
        fromTasks,
        toTasks,
        taskIndex
      });
    }
  },
  computed: {
    ...mapState(["board"])
  },
  mounted() {
    this.getTasksForBoard(this.$route.params.projectId);
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
</style>