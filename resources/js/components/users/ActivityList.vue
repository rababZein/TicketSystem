<template>
  <div class="col-md-9">
    <router-view :key="userId" />
    <div class="card">
      <div class="card-header p-2">
        <ul class="nav nav-pills">
          <li class="nav-item">
            <a class="nav-link active" href="#activity" data-toggle="tab">Activity</a>
          </li>
        </ul>
      </div>
      <!-- /.card-header -->
      <div class="card-body p-0">
        <div class="tab-content">
          <div class="active tab-pane" id="activity">
            <ul class="panel-body list-group" style="height:330px;overflow-y:auto;">
              <li class="list-group-item" v-for="activity in activities.data" :key="activity.id">
                <span
                  style="color:#888;font-style:italic"
                >{{ activity.created_at | DateWithTime }} |</span>
                <router-link :to="'/profile/' + activity.user.id">{{ activity.user.name }}</router-link>

                {{ activity.subject }}
              </li>
            </ul>
          </div>
          <!-- /.tab-pane -->
        </div>
        <!-- /.tab-content -->
      </div>
      <!-- /.card-body -->
      <!-- /.card-header -->
    </div>
    <!-- /.nav-tabs-custom -->
  </div>
</template>

<script>
import { mapGetters } from "vuex";
import moment from "moment";

export default {
  props: {
    userId: {
      type: String,
      required: true
    }
  },
  methods: {
    getlogActivityListsByClientId(id) {
      this.$Progress.start();
      this.$store
        .dispatch("activity/getlogActivityListByClientId", id)
        .then(response => {})
        .catch(error => {
          console.log(error);
        });
    }
  },
  created() {
    this.getlogActivityListsByClientId(this.userId);
  },
  computed: {
    ...mapGetters({
      activities: "activity/activityList"
    })
  },
  beforeRouteUpdate(to, from, next) {
    console.log(to.params);
    this.getlogActivityListsByClientId(to.params.uid);
    next();
  },
  filters: {
    DateWithTime(date) {
      return moment(date).format(" DD/MM/YY - hh:mm a");
    }
  }
};
</script>

<style>
</style>