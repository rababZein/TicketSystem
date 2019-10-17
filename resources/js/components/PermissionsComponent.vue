<template>
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">Permissions Table</h3>

          <div class="card-tools">
            <button type="submit" class="btn btn-success btn-sm" @click="newModal">
              <i class="fas fa-plus fa-fw"></i>
              <span class="d-none d-lg-inline">New Permission</span>
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
              <tr v-for="permission in permissions.data" :key="permission.id">
                <td>{{ permission.id }}</td>
                <td>{{ permission.name }}</td>
                <td>
                  <a href="#" @click="editModel(permission)" class="btn btn-primary btn-xs">
                    <i class="fas fa-edit fa-fw"></i>
                  </a>
                  <a href="#" @click="deletePermission(permission.id)" class="btn btn-danger btn-xs">
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
            :data="permissions"
            @pagination-change-page="getResults"
          ></pagination>
        </div>
        <!-- /.card-body -->
      </div>
      <!-- /.card -->
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
            <h5 v-show="!editMode" class="modal-title" id="newRoleLabel">Create New role</h5>
            <h5 v-show="editMode" class="modal-title" id="newRoleLabel">Edit role</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <form
            @submit.prevent="editMode ? editPermission(form.id) : createPermission()"
            @keydown="form.onKeydown($event)"
          >
            <div class="modal-body">
              <div class="form-group">
                <label for="name">Permission Name</label>
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
              <button v-show="!editMode" type="submit" class="btn btn-primary">Save</button>
              <button v-show="editMode" type="submit" class="btn btn-success">Update</button>
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
      editMode: false,
      form: new Form({
        id: "",
        name: ""
      }),
      permissions: {}
    };
  },
  methods: {
    newModal() {
      this.editMode = false;
      this.form.reset();
      $("#newRole").modal("show");
    },
    editModel(permission) {
      this.editMode = true;
      this.form.reset();
      $("#newRole").modal("show");
      this.form.fill(permission);
    },
    getResults(page = 1) {
      this.$Progress.start();
      this.$api.permissions
        .get({ page: page })
        .then(response => {
          this.permissions = response.data;
          this.$Progress.finish();
        })
        .catch(error => {
          this.$Progress.fail();
        });
    },
    createPermission() {
      this.$Progress.start();
      this.form
        .post("/v-api/permissions")
        .then(response => {
          $("#newRole").modal("hide");
          this.$Progress.finish();
          this.getResults();
          Toast.fire({
            type: "success",
            title: response.data.message
          });
        })
        .catch(error => {
          this.$Progress.fail();
          Toast.fire({
            type: "error",
            title: error.response.data.message
          });
        });
    },
    editPermission(id) {
      this.$Progress.start();
      this.form
        .put("/v-api/permissions/" + id)
        .then(response => {
          $("#newRole").modal("hide");
          this.$Progress.finish();
          this.getResults();
          Toast.fire({
            type: "success",
            title: "permission updated successfully"
          });
        })
        .catch(error => {
          this.$Progress.fail();
          Toast.fire({
            type: "error",
            title: "can't update the permission"
          });
        });
    },
    deletePermission(id) {
      Swal.fire({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, delete it!"
      }).then(result => {
        if (result.value) {
          this.$Progress.start();
          this.$api.permissions
            .delete(id)
            .then(response => {
              this.$Progress.finish();
              this.getResults();
              Swal.fire("Deleted!", "permission has been deleted.", "success");
            })
            .catch(error => {
              this.$Progress.fail();
              Toast.fire({
                type: "error",
                title: "can't delete permission"
              });
            });
        }
      });
    }
  },
  mounted() {
    this.getResults();
  }
};
</script>

