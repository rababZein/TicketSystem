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
    <div class="card-body" v-if="user.metadata">
      <div class="row">
        <div class="form-group col-sm-12 col-md-3">
          <label for="first_name">First Name:</label>
          <div class="font-weight-light">{{ user.metadata.first_name }}</div>
        </div>
        <div class="form-group col-sm-12 col-md-3">
          <label for="last_name">last name:</label>
          <div class="font-weight-light">{{ user.metadata.last_name }}</div>
        </div>
        <div class="form-group col-sm-12 col-md-3">
          <label for="company">Company name:</label>
          <div class="font-weight-light">{{ user.metadata.company }}</div>
        </div>
        <div class="form-group col-sm-12 col-md-3">
          <label for="address">Address:</label>
          <div class="font-weight-light">{{ user.metadata.address }}</div>
        </div>
      </div>
      <hr>
      <div class="row">
        <div class="form-group col-sm-12 col-md-3">
          <label for="language">Language:</label>
          <div class="font-weight-light">{{ user.metadata.language }}</div>
        </div>
        <div class="form-group col-sm-12 col-md-3">
          <label for="language">Gender:</label>
          <div class="font-weight-light">{{ user.metadata.gender }}</div>
        </div>
        <div class="form-group col-sm-12 col-md-3">
          <label for="telephone">Telephone:</label>
          <div class="font-weight-light">{{ user.metadata.telephone }}</div>
        </div>
        <div class="form-group col-sm-12 col-md-3">
          <label for="mobile">Mobile:</label>
          <div class="font-weight-light">{{ user.metadata.mobile }}</div>
        </div>
      </div>
      <hr>
      <div class="row">
        <div class="form-group col-sm-12 col-md-3">
          <label for="website">Website:</label>
          <div class="font-weight-light">{{ user.metadata.website }}</div>
        </div>
        <div class="form-group col-sm-12 col-md-3">
          <label for="birth_date">Birth Date:</label>
          <div class="font-weight-light">{{ user.metadata.birth_date | DateOnly }}</div>
        </div>
        <div class="form-group col-sm-12 col-md-3">
          <label for="fax">Fax:</label>
          <div class="font-weight-light">{{ user.metadata.fax }}</div>
        </div>
      </div>
    </div>
    <div class="card-body text-center" v-else>
      <router-link
          :to="{ name: 'profile.edit', params: {id: this.$route.params.id }}"
          class="btn btn-success btn-xs"
        >
          <i class="fas fa-plus fa-fw"></i> add more datails for this user
        </router-link>
    </div>
  </div>
</template>

<script>
import { mapGetters } from "vuex";

export default {
  computed: {
    ...mapGetters({
      user: "user/activeSingleUser"
    })
  }
};
</script>

<style>
</style>