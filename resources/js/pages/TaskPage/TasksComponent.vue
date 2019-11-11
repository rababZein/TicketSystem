<template>
  <div class="row justify-content-center">
    <div class="col-12">
      <task-list :tasks="tasks"></task-list>
    </div>
    <pagination
      align="right"
      size="small"
      :show-disabled="true"
      :data="tasks"
      :limit="3"
      @pagination-change-page="onPaginate"
    ></pagination>
  </div>
</template>

<script>
import { mapGetters } from "vuex";

export default {
  methods: {
    onPaginate(page) {
      this.$router.push({
        name: "tasks.list",
        params: { page }
      });
    },
    getTasks(page = 1) {
      this.$Progress.start();
      this.$store
        .dispatch("task/getTasks", page)
        .then(response => {
          this.$Progress.finish();
        })
        .catch(error => {
          this.$Progress.fail();
        });
    }
  },
  mounted() {
    this.getTasks(this.$route.params.page || 1);
  },
  beforeRouteUpdate(to, from, next) {
    this.getTasks(to.params.page);
    next();
  },
  computed: {
    ...mapGetters({
      tasks: "task/activeTasks"
    })
  }
};
</script>