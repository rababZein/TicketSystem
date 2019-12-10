<template>
  <div class="board row justify-content-center">
    <div
      v-for="(column, $columnIndex) of board.columns"
      :key="$columnIndex"
      @drop="moveTask($event, column.tasks)"
      @dragover.prevent
      @dragenter.prevent
      class="col-sm-12 col-md-3"
    >
      <div class="card bg-light">
        <div class="card-header">{{ column.name }}</div>
        <div class="card-body p-1">
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
</template>

<script>
import { mapState } from "vuex";

export default {
  data() {
    return {};
  },
  methods: {
    pickupTask(e, taskIndex, fromColumnIndex) {
      e.dataTransfer.effectAllowed = "move";
      e.dataTransfer.dropEffect = "move";
      e.dataTransfer.setData("task-index", taskIndex);
      e.dataTransfer.setData("from-column-index", fromColumnIndex);
    },
    moveTask (e, toTasks) {
      const fromColumnIndex = e.dataTransfer.getData('from-column-index')
      const fromTasks = this.board.columns[fromColumnIndex].tasks
      const taskIndex = e.dataTransfer.getData('task-index')
    
      this.$store.commit('board/MOVE_TASK', {
        fromTasks,
        toTasks,
        taskIndex
      })
    }
  },
  computed: {
    ...mapState(["board"])
  }
};
</script>

<style>
</style>