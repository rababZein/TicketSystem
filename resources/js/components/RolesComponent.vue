<template>
  <div class="container-fluid">
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">Roles Table</h3>

            <div class="card-tools">
              <button
                type="submit"
                class="btn btn-success btn-sm"
                data-toggle="modal"
                data-target="#newRole"
              >
                <i class="fas fa-plus fa-fw"></i>
                <span class="d-none d-lg-inline">New role</span>
              </button>
            </div>
          </div>
          <!-- /.card-header -->
          <div class="card-body table-responsive p-0">
            <table class="table table-hover">
              <thead>
                <tr>
                  <th width="10">ID</th>
                  <th width="80%">name</th>
                  <th>action</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="role in roles.data" :key="role.id">
                  <td>{{ role.id }}</td>
                  <td>{{ role.name }}</td>
                  <td>
                    <a href="#" class="btn btn-info btn-sm">
                      <i class="fas fa-edit fa-fw"></i>
                    </a>
                    <a href="#" class="btn btn-danger btn-sm">
                      <i class="fas fa-trash fa-fw"></i>
                    </a>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
          <div class="card-footer clear-fix">
            <pagination
              align="right"
              size="small"
              :show-disabled="true"
              :data="roles"
              @pagination-change-page="getResults"
            ></pagination>
          </div>
          <!-- /.card-body -->
        </div>
        <!-- /.card -->
      </div>
    </div>

    <!-- Modal -->
    <div
      class="modal fade"
      id="newRole"
      tabindex="-1"
      role="dialog"
      aria-labelledby="newRoleLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="newRoleLabel">Create New role</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <form @submit.prevent="createRole" @keydown="form.onKeydown($event)">
            <div class="modal-body">
              <div class="form-group">
                <label for="name">Role Name</label>
                <input
                  v-model="form.name"
                  type="text"
                  name="name"
                  class="form-control"
                  :class="{ 'is-invalid': form.errors.has('name') }"
                />
                <has-error :form="form" field="name"></has-error>
              </div>
            </div>

            <div class="modal-footer">
              <button type="submit" class="btn btn-primary">Save</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      roles: {},
      form: new Form({
        name: ""
      })
    };
  },
  methods: {
    getResults(page = 1) {
      this.$Progress.start();
      this.$api.roles
        .get({ page: page })
        .then(response => {
          this.roles = response.data;
          this.$Progress.finish();
        })
        .catch(error => {
          this.$Progress.fail();
        });
    },
    createRole() {
      this.$Progress.start();
      this.form
        .post("api/roles")
        .then(response => {
          $("#newRole").modal("hide");
          this.$Progress.finish();
          this.getResults();
          Toast.fire({
            type: "success",
            title: "Role created successfully"
          });
        })
        .catch(error => {
          this.$Progress.fail();
          Toast.fire({
            type: "error",
            title: "can't create new role"
          });
        });
    },
  },
  mounted() {
    this.getResults();
    console.log("Component mounted.");
  }
};
</script>
