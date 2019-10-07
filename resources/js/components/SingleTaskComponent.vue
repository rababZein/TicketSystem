<template>
  <div class="row justify-content-center">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <span>Title:</span>
          <strong>{{ task.name }}</strong>
          <div class="float-right">{{ task.created_at | myDate }}</div>
        </div>

        <div class="card-body">
          <div class="form-group row">
            <label for="Description" class="col-sm-2 col-form-label">Description:</label>
            <div class="col-sm-10">
              <textarea v-model="task.description" class="form-control" id="Description" disabled></textarea>
            </div>
          </div>
          <div class="form-group row">
            <label for="Client" class="col-sm-2 col-form-label">Client:</label>
            <div class="col-sm-10">
              <input v-model="task.client" type="text" class="form-control" id="Client" disabled />
            </div>
          </div>
          <div class="form-group row">
            <label for="Project" class="col-sm-2 col-form-label">Project:</label>
            <div class="col-sm-10">
              <input v-model="task.Project" type="text" class="form-control" id="Project" disabled />
            </div>
          </div>
          <div class="form-group row">
            <label for="Project" class="col-sm-2 col-form-label">Project:</label>
            <div class="col-sm-10">
              <input v-model="task.Project" type="text" class="form-control" id="Project" disabled />
            </div>
          </div>
          <div class="form-group row">
            <label for="Project" class="col-sm-2 col-form-label">Total duration:</label>
            <div class="col-sm-10">{{ task.count_hours }}</div>
          </div>
          <center>
            <div id="duration-text">4 minutes, 35 seconds</div>
          </center>
        </div>
      </div>
      <center class="buttons mb-3">
        <button type="button" class="btn btn-success btn-lg" id="save-button">
          save
          <i class="fas fa-save fa-fw"></i>
        </button>
        <button type="button" class="btn btn-primary btn-lg" id="start-button">
          Start
          <i class="fas fa-play fa-fw"></i>
        </button>
        <button type="button" class="btn btn-info btn-lg" id="stop-button">
          stop
          <i class="fas fa-stop fa-fw"></i>
        </button>
      </center>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      task_id: this.$route.params.id,
      task: {}
    };
  },
  created() {
    this.$api.tasks
      .get(this.task_id)
      .then(response => {
        this.task = response.data.data;
        this.$Progress.finish();
      })
      .catch(error => {
        this.$Progress.fail();
      });
  },
  mounted() {
    console.log("Component mounted.");
  }
};
</script>

<style scoped>
#duration-text {
  font-size: 36px;
  font-weight: 300;
}
</style>