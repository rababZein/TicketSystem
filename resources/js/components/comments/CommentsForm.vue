<template>
  <div class="p-4">
    <hr />
    <form action>
      <vue-editor id="comments-editor" :editorToolbar="customToolbar"></vue-editor>
      <button class="btn float-left btn-primary mt-1">
        Send
        <i class="fab fa-telegram-plane fa-fw"></i>
      </button>
    </form>
  </div>
</template>

<script>
import { VueEditor } from "vue2-editor";

export default {
  data() {
    return {
      customToolbar: [
        ["bold", "italic", "underline", "strike"],
        ["blockquote", "code-block"],
        [{ list: "ordered" }, { list: "bullet" }]
      ]
    };
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