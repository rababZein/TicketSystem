<template>
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
</template>

<script>
import { VueEditor } from "vue2-editor";
import { mapGetters } from "vuex";

export default {
  data() {
    return {
      form: new Form ({
        client_id: this.$route.params.id,
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
    createComment(data) {
      this.$store.dispatch("comment/createComment", data)
        .then()
        .catch()
    }
  },
  components: {
    VueEditor
  }
};
</script>

<style scoped>
#comments-editor {
  height: 100px;
}
</style>