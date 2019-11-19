<template>
  <div class="card" id="listTracking">
    <div class="card-header">
      <h5 class="card-title m-0">User comments</h5>
    </div>
    <div class="card-body">
      <comments v-for="comment in comments.data" :key="comment.id" :comment="comment"></comments>
    </div>
    <div class="p-4">
      <hr />
      <form @submit.prevent="createComment(form)">
          <quill-editor
            id="comments-editor"
            v-model="form.comment"
            ref="myQuillEditor"
            :options="editorOption"
          ></quill-editor>
          <br>
        <button class="btn btn-primary mt-4">
          Send
          <i class="fab fa-telegram-plane fa-fw"></i>
        </button>
      </form>
    </div>
  </div>
</template>

<script>
import { mapGetters } from "vuex";

import { quillEditor } from "vue-quill-editor";

export default {
  data() {
    return {
      client_id: this.$route.params.id,
      form: new Form({
        client_id: this.$route.params.id,
        comment: null
      }),
      editorOption: {
        modules: {
          toolbar: [
            ["bold", "italic", "underline", "strike"],
            ["blockquote", "code-block"],
            [{ list: "ordered" }, { list: "bullet" }]
          ]
        }
      }
    };
  },
  methods: {
    getComments(id) {
      this.$store
        .dispatch("comment/getCommentsPerClient", id)
        .then()
        .catch();
    },
    createComment(data) {
      this.$store
        .dispatch("comment/createCommentForUser", data)
        .then(response => {
          this.form.comment = "";
        })
        .catch();
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
  },
  components: {
    quillEditor
  }
};
</script>

<style scoped>
#comments-editor {
  height: 100px;
}
</style>