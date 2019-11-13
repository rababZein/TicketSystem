<template>
  <div class="col-md-9">
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
                <span style="color:#888;font-style:italic">{{ activity.create_at }}</span>
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
        .then(response => {
        })
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
      activities: "activity/activityList",
    })
  }
};
</script>

<style>
</style>