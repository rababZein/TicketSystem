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
    <pagination
      class="mt-1"
      align="center"
      size="small"
      :show-disabled="true"
      :data="projects"
      :limit="3"
      @pagination-change-page="getProjectPerClient"
    ></pagination>
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
    getProjectPerClient(page = 1) {
      this.$Progress.start();
      this.$store
        .dispatch("project/getProjectPerClient", { id: this.userId, page: page })
        .then(() => {
          this.$Progress.finish();
        })
        .catch(error => {
          this.$Progress.fail();
        });
    }
  },
  mounted() {
    this.getProjectPerClient();
  },
  computed: {
    ...mapGetters({
      projects: "user/ProjectPerClient"
    })
  }
};
</script>

<style></style>
