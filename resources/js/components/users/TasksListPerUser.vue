<template>
  <div class="active tab-pane">
    <div class="card-body table-responsive p-0" style="height:330px;overflow-y:auto;">
      <table class="table table-hover">
        <thead>
          <tr>
            <th>ID</th>
            <th>tasks</th>
            <th>created at</th>
            <th>project</th>
            <th>status</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="tasks in tasks.data" :key="tasks.id">
            <td>{{ tasks.id }}</td>
            <td>
              <router-link :to="'/task/' + tasks.id">{{ tasks.name }}</router-link>
            </td>
            <td>{{ tasks.created_at | DateWithTime }}</td>
            <td>{{ tasks.project.name }}</td>
            <td><div class="badge bg-primary">{{ tasks.status.name }}</div></td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
  <!-- /.tab-pane -->
</template>

<script>
import { mapGetters } from "vuex";

export default {
  props: {
    userId: {
      type: Number,
      required: true
    }
  },
  methods: {
    getTasksPerClient(id) {
      this.$Progress.start();
      this.$store
        .dispatch("task/getTasksPerClient", id)
        .then(() => {
          this.$Progress.finish();
        })
        .catch(error => {
          this.$Progress.fail();
        });
    }
  },
  mounted() {
    this.getTasksPerClient(this.userId);
  },
  computed: {
    ...mapGetters({
      tasks: "user/TasksPerClient"
    })
  }
};
</script>

<style></style>
