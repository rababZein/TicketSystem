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
            <label for="Client" class="col-sm-2 col-form-label">Client name:</label>
            <div class="col-sm-10">
              <input
                v-if="task.project"
                v-model="task.project.owner.name"
                type="text"
                class="form-control"
                id="Client"
                disabled
              />
            </div>
          </div>
          <div class="form-group row">
            <label for="Project" class="col-sm-2 col-form-label">Project name:</label>
            <div class="col-sm-10">
              <input
                v-if="task.project"
                v-model="task.project.name"
                type="text"
                class="form-control"
                id="Project"
                disabled
              />
            </div>
          </div>
          <div class="form-group row">
            <label for="Project" class="col-sm-2 col-form-label">Total duration:</label>
            <div class="col-sm-10">{{ task.count_hours }}</div>
          </div>
          <center>
            <div id="duration-text" v-if="activeTimerString" v-bind:class="{'text-success': activeTimerString}">{{ activeTimerString }}</div>
            <div id="duration-text" v-if="counted_time" v-bind:class="{'text-danger': counted_time}">{{ counted_time }}</div>
          </center>
        </div>
      </div>
      <center class="buttons mb-3">
        <button v-show="editMode" type="button" class="btn btn-success btn-lg" id="save-button">
          save
          <i class="fas fa-save fa-fw"></i>
        </button>
        <button
          @click="startTracking()"
          type="button"
          class="btn btn-primary btn-lg"
          id="start-button"
          v-show="!activeTimerString"
        >
          Start
          <i class="fas fa-play fa-fw"></i>
        </button>
        <button
          @click="stopTracking()"
          v-show="activeTimerString"
          type="button"
          class="btn btn-info btn-lg"
          id="stop-button"
        >
          stop
          <i class="fas fa-stop fa-fw"></i>
        </button>
      </center>
    </div>
  </div>
</template>

<script>
import moment from "moment";

export default {
  data() {
    return {
      editMode: false,
      task_id: this.$route.params.id,
      task: {},
      tracking_task: null,
      counter: { seconds: 0},
      activeTimerString: null,
      counted_time: null
    };
  },
  methods: {
    startTracking() {
      this.$api.track
        .post({
          comment: "new tracking",
          start_at: moment().format("YYYY-MM-DD HH:mm:ss"),
          task_id: this.task_id
        })
        .then(response => {
          this.tracking_task = response.data.data;
          this.startTimer();
        })
        .catch(error => {
          Toast.fire({
            type: "error",
            title: error.response.data.message
          });
        });
    },
    startTimer() {
      let vm = this;
      let started = moment(this.tracking_task.start_at);
      vm.counter.seconds = parseInt(
        moment.duration(moment().diff(started)).asSeconds()
      );
      vm.counter.ticker = setInterval(() => {
        vm.counted_time = null;
        const time = vm._readableTimeFromSeconds(++vm.counter.seconds);
        vm.activeTimerString = `${time.hours}:${time.minutes}:${time.seconds}`;
      }, 1000);
    },
    stopTracking() {
      this.$api.track
        .put({
          track_id: this.tracking_task.id,
          end_at: moment().format("YYYY-MM-DD HH:mm:ss"),
          task_id: this.task_id
        })
        .then(response => {
          this.tracking_task = response.data.data;
          this.$Progress.finish();
          // Stop the ticker
          clearInterval(this.counter.ticker);
          // Reset the counter and timer string
          this.counter = { seconds: 0, timer: null };
          this.activeTimerString = null;
          const trackTime = this._readableTimeFromSeconds(this.tracking_task.count_time);
          this.counted_time = `${trackTime.hours}:${trackTime.minutes}:${trackTime.seconds}`;
        })
        .catch(error => {
          this.$Progress.fail();
          Toast.fire({
            type: "error",
            title: error.response.data.message
          });
        });
    },
    /**
     * Splits seconds into hours, minutes, and seconds.
     */
    _readableTimeFromSeconds: function(seconds) {
      const hours = 3600 > seconds ? 0 : parseInt(seconds / 3600, 10);
      return {
        hours: this._padNumber(hours),
        seconds: this._padNumber(seconds % 60),
        minutes: this._padNumber(parseInt(seconds / 60, 10) % 60)
      };
    },
    /**
     * Conditionally pads a number with "0"
     */
    _padNumber: number => (number > 9 ? number : (number === 0 ? "00" : "0" + number))
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
  mounted() {}
};
</script>

<style scoped>
#duration-text {
  font-size: 36px;
  font-weight: 300;
}
</style>