<template>
  <div class="col-lg-3 col-6">
    <!-- small card -->
    <div class="small-box" :class="cardBg">
      <div class="inner">
        <h3>
          <slot name="count"></slot>
        </h3>

        <p><slot name="title"></slot></p>
      </div>
      <div class="icon">
        <slot name="icon"></slot>
      </div>
      <a href="#" class="small-box-footer"></a>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    cardBg: {
      type: String,
      default: "bg-info"
    }
  }
};
</script>

<style>
</style>