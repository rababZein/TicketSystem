<template>
  <div class="container-fluid">
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">Users Table</h3>

            <div class="card-tools">
              <button
                type="submit"
                class="btn btn-success btn-sm"
                data-toggle="modal"
                data-target="#newUser"
              >
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
                  <th>created at</th>
                  <th>action</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="user in users.data" :key="user.id">
                  <td>{{user.id}}</td>
                  <td>{{user.name}}</td>
                  <td>{{user.email}}</td>
                  <td>{{user.created_at | myDate}}</td>
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
          <!-- /.card-body -->
        </div>
        <!-- /.card -->
      </div>
    </div>

    <!-- Modal -->
    <div
      class="modal fade"
      id="newUser"
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
          <form @submit.prevent="createUser" @keydown="form.onKeydown($event)">
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
      users: {},
      form: new Form({
        id: "",
        name: "",
        email: ""
      })
    };
  },
  methods: {
    getResults() {
      this.$Progress.start()
      axios.get("api/user").then(response => {
        this.users = response.data;
        this.$Progress.finish();
      }).catch(error => {
        this.$Progress.fail();
      });
    },
    createUser() {
      this.$Progress.start()
      this.form.post("api/user").then(response => {
        $('#newUser').modal('hide');
        this.$Progress.finish();
        this.getResults();
      }).catch(error => {
        this.$Progress.fail();
      });
    }
  },
  mounted() {
    this.getResults();
    console.log("Component mounted.");
  }
};
</script>
