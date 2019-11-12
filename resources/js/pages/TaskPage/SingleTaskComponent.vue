<template>
  <div class="row justify-content-center">
    <div class="col-md-12" v-show="task.id">
      <div class="card">
        <div class="card-header">
          <span>Title:</span>
          <span class="font-weight-light">{{ task.name }}</span>
          <div class="float-right font-weight-light">{{ task.created_at | DateOnly }}</div>
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
          <div class="form-group row" v-show="duration">
            <label for="Project" class="col-sm-2 col-form-label">
              Total duration:
              <p>
                <small>(hours:minutes)</small>
              </p>
            </label>
            <div class="col-sm-10">
              <p class="font-weight-light mt-3">{{ humanReadableFromSecounds(duration).slice(0, -3) }}</p>
            </div>
          </div>
          <center>
            <div
              id="duration-text"
              v-if="activeTimerString"
              v-bind:class="{'text-success': activeTimerString}"
            >{{ activeTimerString }}</div>
            <div
              id="duration-text"
              v-else
              v-bind:class="{'text-danger': counted_time}"
            >{{ counted_time }}</div>
          </center>
        </div>
      </div>
      <center class="buttons mb-3">
        <button v-show="editMode" type="button" class="btn btn-success btn-lg" id="save-button">
          save
          <i class="fas fa-save fa-fw"></i>
        </button>
        <div class="btn-group dropup">
          <button
            class="btn btn-dark dropdown-toggle btn-lg"
            data-toggle="dropdown"
            type="button"
            aria-expanded="false"
          >
            More Actions
            <span class="caret"></span>
          </button>
          <ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">
            <li v-show="!activeTimerString">
              <a @click="listTrackingTask(task_id)" class="dropdown-item">
                <i class="fas fa-edit fa-fw mr-2"></i>
                Edit History
              </a>
            </li>
          </ul>
        </div>

        <button
          @click="startTracking"
          type="button"
          class="btn btn-primary btn-lg"
          id="start-button"
          v-show="!activeTimerString"
        >
          Start
          <i class="fas fa-play fa-fw"></i>
        </button>
        <button
          @click="stopTracking"
          v-show="activeTimerString"
          type="button"
          class="btn btn-info btn-lg"
          id="stop-button"
        >
          stop
          <i class="fas fa-stop fa-fw"></i>
        </button>
      </center>
      <div class="card" id="listTracking" v-show="listTracking_Task.length > 0">
        <div class="card-header">
          <h5 class="card-title m-0">History</h5>
        </div>
        <div class="card-body">
          <div class="callout callout-info" v-for="item in orderedTrack" :key="item.id">
            <div class="row">
              <div class="col-sm-4">
                <label for="started_at" class="col-form-label">started at:</label>
                <span class="font-weight-light">{{ item.start_at | DateWithTime }}</span>
              </div>
              <div class="col-sm-4">
                <label for="end_at" class="col-form-label strong">end at:</label>
                <span class="font-weight-light">{{ item.end_at | DateWithTime }}</span>
              </div>
              <div class="col-sm-2">
                <label for="duration" class="col-form-label">duration:</label>
                <span class="font-weight-light">{{ humanReadableFromSecounds(item.count_time) }}</span>
              </div>
              <div class="col-sm-2 text-right" id="action-buttons">
                <button @click="editModel(item)" class="btn btn-primary btn-sm">
                  <div class="fa fa-edit fa-fw"></div>
                </button>
                <button @click="deleteTrack(task.id, item.id)" class="btn btn-danger btn-sm">
                  <div class="fa fa-trash fa-fw"></div>
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- Modal -->
    <div
      class="modal fade"
      id="Modal"
      tabindex="-1"
      role="dialog"
      aria-labelledby="Modal"
      aria-hidden="true"
    >
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="newRoleLabel">Edit Track</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <form
            @submit.prevent="editTrackingTime(task_id,form.id)"
            @keydown="form.onKeydown($event)"
          >
            <div class="modal-body">
              <div class="form-group">
                <label for="start_at">started at</label>
                <date-picker
                  v-model="form.start_at"
                  lang="en"
                  type="datetime"
                  format="YYYY-MM-DD HH:mm:ss"
                  :minute-step="1"
                  value-type="format"
                  input-class="form-control"
                ></date-picker>
                <has-error :form="form" field="start_at"></has-error>
              </div>
              <div class="form-group">
                <label for="end_at">end at</label>
                <date-picker
                  v-model="form.end_at"
                  lang="en"
                  type="datetime"
                  format="YYYY-MM-DD HH:mm:ss"
                  :minute-step="1"
                  input-class="form-control"
                  value-type="format"
                ></date-picker>
                <has-error :form="form" field="end_at"></has-error>
              </div>
            </div>

            <div class="modal-footer">
              <button type="submit" class="btn btn-success">Update</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import moment from "moment";
import DatePicker from "vue2-datepicker";
import trackApi from '../../api/tracks';
import taskApi from '../../api/tasks';

export default {
  components: { DatePicker },
  data() {
    return {
      editMode: false,
      task_id: this.$route.params.id,
      task: {},
      tracking_task: null,
      counter: { seconds: 0 },
      activeTimerString: null,
      counted_time: null,
      duration: "0",
      listTracking_Task: [],
      form: new Form({
        id: "",
        start_at: "",
        end_at: ""
      })
    };
  },
  methods: {
    startTracking() {
      // Reset the counter and timer string
      this.counted_time = null;
      // show timer before send request
      this.activeTimerString = this.humanReadableFromSecounds(this.duration);
      trackApi
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
        vm.activeTimerString = vm.humanReadableFromSecounds(++vm.duration);
      }, 1000);
    },
    stopTracking() {
      this.$Progress.start();
      trackApi
        .put({
          track_id: this.tracking_task.id,
          end_at: moment().format("YYYY-MM-DD HH:mm:ss"),
          task_id: this.task_id
        })
        .then(response => {
          this.tracking_task = response.data.data;
          // count duration for this task
          this.countTaskDuration(this.task_id);
          this.$Progress.finish();
          // Stop the ticker
          clearInterval(this.counter.ticker);
          this.activeTimerString = null;
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
    _padNumber: number =>
      number > 9 ? number : number === 0 ? "00" : "0" + number,

    // Count Duration for a specfic task.
    countTaskDuration(task_id) {
      trackApi
        .countDuration(task_id)
        .then(response => {
          this.duration = response.data.data.tracking;
          this.counted_time = this.humanReadableFromSecounds(this.duration);
        })
        .catch(error => {
          Toast.fire({
            type: "error",
            title: error.response.data.message
          });
        });
    },
    // fun to check if this track is in progress
    checkTrackingInProgress(task_id) {
      trackApi
        .checkTrackingInProgress(task_id)
        .then(response => {
          this.tracking_task = response.data.data;
          this.startTimer();
        })
        .catch(error => {
          // console.log(error);
        });
    },
    // list all Tracking_Task for this task
    listTrackingTask(task_id) {
      this.$Progress.start();
      trackApi
        .getHistory(task_id)
        .then(response => {
          this.listTracking_Task = response.data.data;

          this.$Progress.finish();
        })
        .catch(error => {
          this.$Progress.fail();
        });
    },
    humanReadableFromSecounds(seconds) {
      let duration = this._readableTimeFromSeconds(seconds);
      return `${duration.hours}:${duration.minutes}:${duration.seconds}`;
    },
    editModel(track) {
      this.form.reset();
      $("#Modal").modal("show");
      this.form.fill(track);
    },
    editTrackingTime(task_id, track_id) {
      this.$Progress.start();
      this.form
        .patch("/v-api/tracking/" + task_id + "/" + track_id)
        .then(response => {
          $("#Modal").modal("hide");
          this.$Progress.finish();
          this.listTrackingTask(task_id);
          this.countTaskDuration(task_id);
          Toast.fire({
            type: "success",
            title: response.data.message
          });
        })
        .catch(error => {
          this.$Progress.fail();
        });
    },
    deleteTrack(task_id, track_id) {
      Swal.fire({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, delete it!"
      }).then(result => {
        if (result.value) {
          this.$Progress.start();
          trackApi
            .delete({ task_id, track_id })
            .then(response => {
              this.$Progress.finish();
              this.listTrackingTask(task_id);
              this.countTaskDuration(task_id);
              Swal.fire("Deleted!", response.data.message, "success");
            })
            .catch(error => {
              this.$Progress.fail();
              Toast.fire({
                type: "error",
                title: error.response.data.message
              });
            });
        }
      });
    }
  },

  created() {
    // check if this track is in progress
    this.checkTrackingInProgress(this.task_id);

    taskApi
      .show(this.task_id)
      .then(response => {
        this.task = response.data.data;
        this.$Progress.finish();
      })
      .catch(error => {
        this.$Progress.fail();
      });
    // count total duration
    this.countTaskDuration(this.task_id);
  },
  mounted() {},
  computed: {
    orderedTrack: function() {
      return this.listTracking_Task.reverse();
    }
  },
  filters: {
    DateWithTime(data) {
      return moment(data).format(" DD/MM/YYYY - hh:mm:ss a");
    },
    DateOnly(data) {
      return moment(data).format(' DD/MM/YYYY');
    }
  }
};
</script>

<style scoped>
#duration-text {
  font-size: 36px;
  font-weight: 300;
}
.mx-datepicker {
  display: block;
  width: 100%;
}
.invalid-feedback {
  display: inline;
}
</style>