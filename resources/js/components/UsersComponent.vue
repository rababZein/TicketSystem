<template>
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">Users Table</h3>

          <div class="card-tools">
            <button type="submit" class="btn btn-success btn-sm" @click="newModal">
              <i class="fas fa-plus fa-fw"></i>
              <span class="d-none d-lg-inline">New user</span>
            </button>
          </div>
        </div>
        <!-- /.card-header -->
        <div class="card-body table-responsive p-0">
          <table class="table table-hover">
            <thead>
              <tr>
                <th>ID</th>
                <th>name</th>
                <th>email</th>
                <th>Role</th>
                <th>user type</th>
                <th>created at</th>
                <th>action</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="user in users.data" :key="user.id">
                <td>{{user.id}}</td>
                <td>{{user.name}}</td>
                <td>{{user.email}}</td>
                <td>
                  <div
                    v-show="user.roles"
                    v-for="role in user.roles"
                    :key="role"
                    class="badge badge-primary mr-1"
                  >{{ role.name }}</div>
                </td>
                <td>{{ user.type }}</td>
                <td>{{user.created_at | myDate}}</td>
                <td>
                  <a href="#" class="btn btn-primary btn-xs" @click="editModal(user)">
                    <i class="fas fa-edit fa-fw"></i>
                  </a>
                  <a href="#" class="btn btn-danger btn-xs" @click="deleteUser(user.id)">
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
            :data="users"
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
      id="Modal"
      tabindex="-1"
      role="dialog"
      aria-labelledby="newUserLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="newUserLabel">Create New User</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <form
            @submit.prevent="editMode ? editUser(form.id) : createUser()"
            @keydown="form.onKeydown($event)"
          >
            <div class="modal-body">
              <div class="form-group">
                <label for="name">Username</label>
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
                <label for="email">Email</label>
                <input
                  v-model="form.email"
                  type="email"
                  name="email"
                  class="form-control"
                  :class="{ 'is-invalid': form.errors.has('email') }"
                />
                <has-error :form="form" field="email"></has-error>
              </div>
              <div class="form-group">
                <label for="password">Password</label>
                <input
                  v-model="form.password"
                  type="password"
                  name="password"
                  class="form-control"
                  :class="{ 'is-invalid': form.errors.has('password') }"
                />
                <has-error :form="form" field="password"></has-error>
              </div>
              <div class="form-group">
                <label for="role">Role</label>
                <multiselect
                  v-model="form.roles"
                  :multiple="true"
                  :options="roles"
                  :close-on-select="true"
                  placeholder="Select one"
                  label="name"
                  track-by="name"
                ></multiselect>
                <has-error :form="form" field="role"></has-error>
              </div>
              <div class="form-group">
                <label for="type">type</label>
                <multiselect
                  v-model="form.type"
                  :options="types"
                  :searchable="false"
                  :close-on-select="true"
                  :show-labels="false"
                  placeholder="Pick a value"
                ></multiselect>
                <has-error :form="form" field="type"></has-error>
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
      editMode: false,
      users: {},
      form: new Form({
        id: "",
        name: "",
        email: "",
        password: "",
        roles: "",
        type: ""
      }),
      roles: [],
      types: ["regular-user", "client"]
    };
  },
  methods: {
    getResults(page = 1) {
      this.$Progress.start();
      this.$api.users
        .get({ page: page })
        .then(response => {
          this.users = response.data;
          this.$Progress.finish();
        })
        .catch(error => {
          this.$Progress.fail();
        });
    },
    newModal() {
      this.editMode = false;
      this.form.reset();
      $("#Modal").modal("show");
    },
    editModal(item) {
      this.editMode = true;
      this.form.reset();
      $("#Modal").modal("show");
      this.form.fill(item);
      this.form.roles = _.map(this.form.roles, function(value) {
        return { name: value.name };
      });
    },
    createUser() {
      this.$Progress.start();
      this.form
        .post("/v-api/users/")
        .then(response => {
          $("#Modal").modal("hide");
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
    editUser(id) {
      this.$Progress.start();
      this.form
        .put("/v-api/users/" + id)
        .then(response => {
          $("#Modal").modal("hide");
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
    getAllRoles() {
      this.$api.roles
        .getAll()
        .then(response => {
          console.log(response);
          this.roles = _.map(response.data.data, function(key, value) {
            return { id: key.id, name: key.name };
          });
          console.log($this.roles);
          this.$Progress.finish();
        })
        .catch(error => {
          this.$Progress.fail();
        });
    },
    deleteUser(id) {
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
          this.$api.users
            .delete(id)
            .then(response => {
              this.$Progress.finish();
              this.getResults();
              Swal.fire("Deleted!", response.data.message, "success");
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
    this.getAllRoles();
    console.log("Component mounted.");
  }
};
</script>

<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>
