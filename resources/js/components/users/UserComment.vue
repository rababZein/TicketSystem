<template>
  <div class="card" id="listTracking">
    <div class="card-header">
      <h5 class="card-title m-0">User comments</h5>
    </div>
    <div class="card-body">
      <comments v-for="comment in comments" :key="comment.id" :comment="comment"></comments>
    </div>
    <comments-form></comments-form>
  </div>
</template>

<script>
import { mapGetters } from "vuex";

export default {
  data() {
    return {
      client_id: this.$route.params.id
      // comments: [
      //   {
      //     id: 1,
      //     body:
      //       "It is a long established fact that a reader will be distracted by the readable content ",
      //     user: { name: "mohamed" },
      //     created_at: "07/11/19 - 03:33 pm"
      //   },
      //   {
      //     id: 2,
      //     body:
      //       "It is a long established fact that a reader will be distracted by the readable content ",
      //     user: { name: "mohamed" },
      //     created_at: "07/11/19 - 03:33 pm"
      //   }
      // ]
    };
  },
  methods: {
    getComments(id) {
      this.$store.dispatch("comment/getCommentsPerClient", id)
      .then()
      .catch()
    }
  },
  mounted() {
    // get all comments for the user
    this.getComments(this.client_id);
  },
  computed: {
    ...mapGetters({
      comments: "comment/activeComments"
    })
  }
};
</script>

<style>
</style>