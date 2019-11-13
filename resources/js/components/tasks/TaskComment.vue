<template>
  <div class="card" id="listTracking">
    <div class="card-header">
      <h5 class="card-title m-0">comments</h5>
    </div>
    <div class="card-body">
      <div class="post">
        <div class="user-block">
          <img
            class="img-circle img-bordered-sm"
            src="/../../assets/img/profile.png"
            alt="user image"
          />
          <span class="username">
            <a href="#">Jonathan Burke Jr.</a>
          </span>
          <span class="description">7:30 PM today</span>
        </div>
        <!-- /.user-block -->
        <p>
          Lorem ipsum represents a long-held tradition for designers,
          typographers and the like. Some people hate it and argue for
          its demise, but others ignore the hate as they create awesome
          tools to help create filler text for everyone from bacon lovers
          to Charlie Sheen fans.
        </p>
      </div>
      <div class="post">
        <div class="user-block">
          <img
            class="img-circle img-bordered-sm"
            src="/../../assets/img/profile.png"
            alt="user image"
          />
          <span class="username">
            <a href="#">Admin</a>
          </span>
          <span class="description">7:30 PM today</span>
        </div>
        <!-- /.user-block -->
        <p>
          Lorem ipsum represents a long-held tradition for designers,
          typographers and the like. Some people hate it and argue for
          its demise, but others ignore the hate as they create awesome
          tools to help create filler text for everyone from bacon lovers
          to Charlie Sheen fans.
        </p>
      </div>
      <div class="post">
        <div class="user-block">
          <img
            class="img-circle img-bordered-sm"
            src="/../../assets/img/profile.png"
            alt="user image"
          />
          <span class="username">
            <a href="#">Jonathan Burke Jr.</a>
          </span>
          <span class="description">7:30 PM today</span>
        </div>
        <!-- /.user-block -->
        <p>
          Lorem ipsum represents a long-held tradition for designers,
          typographers and the like. Some people hate it and argue for
          its demise, but others ignore the hate as they create awesome
          tools to help create filler text for everyone from bacon lovers
          to Charlie Sheen fans.
        </p>
      </div>
    </div>
    <div class="p-4">
      <vue-editor v-model="content" id="editor1"></vue-editor>
    </div>
  </div>
</template>

<script>
import { VueEditor } from "vue2-editor";
export default {
  data() {
    return {
      content: ""
    };
  },
  components: {
    VueEditor
  }
};
</script>

<style>
.post .user-block {
  margin-bottom: 15px;
  width: 100%;
}
.user-block {
  float: left;
}
.post {
    border-bottom: 1px solid #adb5bd;
    color: #666;
    margin-bottom: 15px;
    padding-bottom: 15px;
}
#editor1{
  height: 100px;
}
</style>