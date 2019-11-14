<template>
  <div class="post">
    <div class="user-block">
      <img class="img-circle img-bordered-sm" src="/../../assets/img/profile.png" alt="user image" />
      <span class="username">
        <a href="#">{{ comment.user.name }}</a>
      </span>
      <span class="description">{{ comment.created_at }}</span>
    </div>
    <!-- /.user-block -->
    <p>
      {{ comment.body }}
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