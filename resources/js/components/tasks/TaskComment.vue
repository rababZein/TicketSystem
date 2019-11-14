<template>
  <div class="card" id="comments">
    <div class="card-header">
      <h5 class="card-title m-0">comments</h5>
    </div>
    <div class="card-body">
      <comments v-for="comment in comments" :key="comment.id" :comment="comment"></comments>
    </div>
    <comments-form></comments-form>
  </div>
</template>

<script>
export default {
  data() {
    return {
      comments: [
        {
          id: 1,
          body:
            "It is a long established fact that a reader will be distracted by the readable content ",
          user: { name: "mohamed" },
          created_at: "07/11/19 - 03:33 pm"
        },
        {
          id: 2,
          body:
            "It is a long established fact that a reader will be distracted by the readable content ",
          user: { name: "mohamed" },
          created_at: "07/11/19 - 03:33 pm"
        }
      ]
    };
  }
};
</script>

<style>

</style>