<template>
  <div class="card">
    <div class="card-header">
      <span class="card-title font-weight-light">Profile Details</span>
      <div class="card-tools">
        <router-link
          :to="{ name: 'profile.edit', params: {id: this.$route.params.id }}"
          class="btn btn-primary btn-xs"
        >
          <i class="fas fa-edit fa-fw"></i> edit profile
        </router-link>
      </div>
    </div>
    <div class="card-body">

    </div>
  </div>
</template>

<script>
export default {};
</script>

<style>
</style>