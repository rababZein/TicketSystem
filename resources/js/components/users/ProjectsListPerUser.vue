<template>
  <div class="active tab-pane">
    <div class="card-body table-responsive p-0" style="height:330px;overflow-y:auto;">
      <table class="table table-hover">
        <thead>
          <tr>
            <th>ID</th>
            <th>Project</th>
            <th>created at</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="project in projects.data" :key="project.id">
            <td>{{ project.id }}</td>
            <td><router-link :to="'/project/' + project.id">{{ project.name }}</router-link></td>
            <td>{{ project.created_at | DateWithTime }}</td>
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
    getProjectPerClient(id) {
      this.$Progress.start();
      this.$store
        .dispatch("project/getProjectPerClient", id)
        .then(() => {
          this.$Progress.finish();
        })
        .catch(error => {
          this.$Progress.fail();
        });
    }
  },
  mounted() {
    this.getProjectPerClient(this.userId);
  },
  computed: {
    ...mapGetters({
      projects: "user/ProjectPerClient"
    })
  }
};
</script>

<style></style>
