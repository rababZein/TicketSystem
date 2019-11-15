<template>
  <div class="card" id="comments">
    <div class="card-header">
      <h5 class="card-title m-0">comments</h5>
    </div>
    <div class="card-body">
      <comments v-for="comment in comments" :key="comment.id" :comment="comment"></comments>
    </div>
    <div class="p-4">
      <hr />
      <form @submit.prevent="createComment(form)">
        <vue-editor id="comments-editor" v-model="form.comment" :editorToolbar="customToolbar"></vue-editor>
        <button class="btn float-left btn-primary mt-1">
          Send
          <i class="fab fa-telegram-plane fa-fw"></i>
        </button>
      </form>
    </div>
  </div>
</template>

<script>
import { mapGetters } from "vuex";
import { VueEditor } from "vue2-editor";

export default {
  data() {
    return {
      taskId: this.$route.params.id,
      form: new Form({
        task_id: this.$route.params.id,
        comment: ""
      }),
      customToolbar: [
        ["bold", "italic", "underline", "strike"],
        ["blockquote", "code-block"],
        [{ list: "ordered" }, { list: "bullet" }]
      ]
    };
  },
  methods: {
    getComments(id) {
      this.$store
        .dispatch("comment/getCommentsPerTask", id)
        .then()
        .catch();
    },
    createComment(data) {
      this.$store
        .dispatch("comment/createCommentForTask", data)
        .then()
        .catch();
    }
  },
  mounted() {
    // get all comments for the user
    this.getComments(this.taskId);
  },
  computed: {
    ...mapGetters({
      comments: "comment/activeComments"
    })
  },
  components: {
    VueEditor
  }
};
</script>

<style>
</style>