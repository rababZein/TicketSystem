<template>
  <div class="card mt-2">
    <div class="card-body table-responsive p-0">
      <table class="table table-hover table-sm">
        <thead>
          <tr>
            <th width="10%">Date</th>
            <th width="10%">Client</th>
            <th width="20%">Project name</th>
            <th width="20%">time counting</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="item in time_counting.data" :key="item.id">
            <td>{{ item.the_day | DateOnly}}</td>
            <td>{{ item.owner_name }}</td>
            <td>{{ item.project_name }}</td>
            <td>{{ item.time_counting }}</td>
          </tr>
        </tbody>
      </table>
    </div>
    <!-- /.card-body -->
  </div>
</template>

<script>
export default {
    props: {
    time_counting: {
      type: Object,
      required: true
    }
  },
};
</script>

<style>
</style>