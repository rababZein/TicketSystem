<template>
  <div class="">
    <div class="error-page">
        <h2 class="headline text-danger"> 404</h2>

        <div class="error-content">
          <h3><i class="fas fa-exclamation-triangle text-danger"></i> Oops! Page not found.</h3>

          <p>
            We could not find the page you were looking for.
            Meanwhile, you may <router-link to="/dashboard">return to dashboard</router-link> or try using the search form.
          </p>

        </div>
        <!-- /.error-content -->
      </div>
  </div>
</template>

<script>
export default {

}
</script>

<style>

</style>