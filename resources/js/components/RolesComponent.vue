<template>
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">Roles Table</h3>

          <div class="card-tools">
            <button type="submit" class="btn btn-success btn-sm" @click="newModel">
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
                <th width="20%">name</th>
                <th width="60%">permissions</th>
                <th>action</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="role in roles.data" :key="role.id">
                <td>{{ role.id }}</td>
                <td>{{ role.name }}</td>
                <td>
                  <span
                    v-for="item in role.permissions"
                    :key="item.id"
                    class="badge badge-danger mr-1"
                  >{{ item.name }}</span>
                </td>
                <td>
                  <a href="#" @click="editModel(role)" class="btn btn-info btn-sm">
                    <i class="fas fa-edit fa-fw"></i>
                  </a>
                  <a href="#" @click="deleteRole(role.id)" class="btn btn-danger btn-sm">
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
            @submit.prevent="editMode ? editRole(form.id) : createRole()"
            @keydown="form.onKeydown($event)"
          >
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
              <div class="form-group">
                <label for="permissions">Permissions</label>
                <multiselect
                  v-model="form.permissions"
                  :options="permissions"
                  :multiple="true"
                  :close-on-select="false"
                  :clear-on-select="false"
                  :preserve-search="true"
                  placeholder="Pick some"
                  label="name"
                  track-by="name"
                  :preselect-first="true"
                >
                  <template slot="selection" slot-scope="{ values, search, isOpen }">
                    <span
                      class="multiselect__single"
                      v-if="values.length &amp;&amp; !isOpen"
                    >{{ values.length }} options selected</span>
                  </template>
                </multiselect>
                <has-error :form="form" field="permissions"></has-error>
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
      roles: {},
      form: new Form({
        id: "",
        name: "",
        permissions: [],
        selected: null
      }),
      selected: null,
      permissions: []
    };
  },
  methods: {
    getResults(page = 1) {
      this.$Progress.start();
      this.$api.roles
        .get({ page: page })
        .then(response => {
          this.roles = response.data.data;
          this.$Progress.finish();
        })
        .catch(error => {
          this.$Progress.fail();
        });
    },
    newModel() {
      this.editMode = false;
      this.form.reset();
      $("#newRole").modal("show");
    },
    editModel(role) {
      this.editMode = true;
      this.form.reset();
      $("#newRole").modal("show");
      this.form.fill(role);

      this.form.selected = _.map(this.form.permissions, function(value, key) {
        return value.name;
      });
    },
    getPermissions() {
      this.$api.permissions
        .getAll()
        .then(response => {
          this.permissions = _.map(response.data.data, function(key, value) {
            return { id: key.id, name: key.name };
          });
          this.$Progress.finish();
        })
        .catch(error => {
          this.$Progress.fail();
        });
    },
    createRole() {
      this.$Progress.start();
      this.form
        .post("/v-api/roles/")
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
    editRole(id) {
      this.$Progress.start();
      this.form
        .put("/v-api/roles/" + id)
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
    deleteRole(id) {
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
          this.$api.roles
            .delete(id)
            .then(response => {
              this.$Progress.finish();
              this.getResults();
              Swal.fire("Deleted!", "The role has been deleted.", "success");
            })
            .catch(error => {
              this.$Progress.fail();
              Toast.fire({
                type: "error",
                title: "can't delete the role"
              });
            });
        }
      });
    }
  },
  mounted() {
    this.getResults();
    this.getPermissions();
    console.log("Component mounted.");
  }
};
</script>

<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>
