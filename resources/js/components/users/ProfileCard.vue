<template>
  <div class="col-md-3">
    <!-- Profile Image -->
    <div class="card card-primary card-outline">
      <div class="card-body box-profile">
        <div class="text-center">
          <img
            class="profile-user-img img-fluid img-circle"
            src="/../assets/img/profile.png"
            alt="User profile picture"
          />
        </div>

        <h3 class="profile-username text-center">{{ user.name }}</h3>
        <p class="text-muted text-center">{{ user.type }}</p>
        <ul class="list-group list-group-unbordered mb-3">
          <li class="list-group-item">
            <b>Projects</b>
            <a class="float-right">2</a>
          </li>
          <li class="list-group-item">
            <b>Tickets</b>
            <a class="float-right">543</a>
          </li>
          <li class="list-group-item">
            <b>Tasks</b>
            <a class="float-right">100</a>
          </li>
        </ul>
      </div>
      <!-- /.card-body -->
    </div>
    <!-- /.card -->
  </div>
</template>

<script>
export default {
    props: {
    user: {
      type: Object,
      required: true
    },
  },
};
</script>

<style>
</style>