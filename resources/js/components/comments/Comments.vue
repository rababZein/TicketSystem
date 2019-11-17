<template>
  <div class="post">
    <div class="user-block">
      <img class="img-circle img-bordered-sm" src="/../../assets/img/profile.png" alt="user image" />
      <span class="username">
        <div v-if="comment.user">{{ comment.user.name }}</div>
      </span>
      <span class="description">{{ comment.created_at | DateWithTime}}</span>
    </div>
    <!-- /.user-block -->
    <p v-html="comment.comment">
    </p>
  </div>
</template>

<script>
export default {
    props: {
        comment: {
            type:Object
        }
    }
};
</script>

<style scpoed>
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
</style>