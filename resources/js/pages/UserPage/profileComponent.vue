<template>
  <div class="justify-content-center" v-if="!loading">
    <div class="row">
      <!-- card box -->
      <card-box></card-box>
      <!-- card box -->
      <div class="col-lg-3 col-6">
        <!-- small card -->
        <div class="small-box bg-success">
          <div class="inner">
            <h3>543</h3>

            <p>Tickets</p>
          </div>
          <div class="icon">
            <i class="fas fa-chart-pie"></i>
          </div>
          <a href="#" class="small-box-footer"></a>
        </div>
      </div>
      <div class="col-lg-3 col-6">
        <!-- small card -->
        <div class="small-box bg-warning">
          <div class="inner">
            <h3>50</h3>

            <p>open Tasks</p>
          </div>
          <div class="icon">
            <i class="fas fa-tasks"></i>
          </div>
          <a href="#" class="small-box-footer"></a>
        </div>
      </div>
      <div class="col-lg-3 col-6">
        <!-- small card -->
        <div class="small-box bg-danger">
          <div class="inner">
            <h3>50</h3>

            <p>closed tasks</p>
          </div>
          <div class="icon">
            <i class="fas fa-list-ul"></i>
          </div>
          <a href="#" class="small-box-footer"></a>
        </div>
      </div>
    </div>
    <div class="row">
      <!-- profile card -->
      <profile-card :user="user"></profile-card>
      <!-- /profile card -->
      <!-- activity list -->
      <activity-list></activity-list>
      <!-- /activity list -->
    </div>
  </div>
  <div class="card" v-else>
    <div class="card-body justify-content-center">loading...</div>
  </div>
</template>

<script>
import { mapGetters } from "vuex";

export default {
  data() {
    return {
      userId: this.$route.params.id,
      loading: true
    };
  },
  methods: {
    getUserById(id) {
      this.$Progress.start();
      this.$store
        .dispatch("user/getUserById", id)
        .then(response => {
          this.$Progress.finish();
          this.loading = false;
        })
        .catch(error => {
          this.$Progress.fail();
        });
    }
  },
  created() {
    this.getUserById(this.userId);
  },
  computed: {
    ...mapGetters({
      user: "user/activeSingleUser",
    })
  }
};
</script>