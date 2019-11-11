<template>
  <div class="col-lg-3 col-6">
    <!-- small card -->
    <div class="small-box" :class="cardBg">
      <div class="inner">
        <h3>{{ count }}</h3>

        <p>{{ title }}</p>
      </div>
      <div class="icon">
        <i :class="icon"></i>
      </div>
      <a href="#" class="small-box-footer"></a>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    count: {
      type: Number,
      required: true,
      default: 0
    },
    cardBg: {
      type: String,
      default: "bg-info"
    },
    icon: {
      type: String,
      default: "fas fa-chart-pie"
    },
    title: {
      type: String,
      required: true
    }
  }
};
</script>

<style>
</style>