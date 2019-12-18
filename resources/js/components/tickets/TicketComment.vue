<template>
  <div class="card" id="comments">
    <div class="card-header">
      <h5 class="card-title m-0">comments</h5>
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
        <br />
        <div class="custom-control custom-checkbox mt-4">
          <input
            v-model="form.send_mail"
            class="custom-control-input"
            :class="{ 'is-invalid': form.errors.has('send_mail') }"
            type="checkbox"
            id="send_mail"
          />
          <label for="send_mail" class="custom-control-label">send as reply to client</label>
        </div>
        <br />
        <button class="btn btn-primary">
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

// require styles
import "quill/dist/quill.core.css";
import "quill/dist/quill.snow.css";
import "quill/dist/quill.bubble.css";

export default {
  data() {
    return {
      ticketId: this.$route.params.id,
      form: new Form({
        ticket_id: this.$route.params.id,
        comment: "",
        send_mail: false
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
        .dispatch("comment/getCommentsPerTicket", id)
        .then()
        .catch();
    },
    createComment(data) {
      this.$store
        .dispatch("comment/createCommentForTicket", data)
        .then(response => {
          this.form.comment = null;
        })
        .catch();
    }
  },
  mounted() {
    // get all comments for the user
    this.getComments(this.ticketId);
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