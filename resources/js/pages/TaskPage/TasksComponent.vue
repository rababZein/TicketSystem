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
      @pagination-change-page="getTasks"
    ></pagination>
  </div>
</template>

<script>
import { mapGetters } from "vuex";

export default {
  methods: {
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
    this.getTasks();
  },
  computed: {
    ...mapGetters({
      tasks: "task/activeTasks"
    })
  }
};
</script>