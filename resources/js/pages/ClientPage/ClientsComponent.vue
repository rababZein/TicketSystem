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
          <vue-bootstrap4-table
            v-if="resultUsers"
            :rows="resultUsers"
            :columns="columns"
            :config="config"
            @on-change-query="onChangeQuery"
            :total-rows="total_rows"
            :classes="classes"
            @on-download="onChangeQuery"
          >
            <template slot="sort-asc-icon">
              <i class="fas fa-sort-up"></i>
            </template>
            <template slot="sort-desc-icon">
              <i class="fas fa-sort-down"></i>
            </template>
            <template slot="no-sort-icon">
              <i class="fas fa-sort"></i>
            </template>
            <template slot="name" slot-scope="props">
              <router-link :to="'/admin/profile/' + props.row.id">{{ props.cell_value }}</router-link>
            </template>
            <template v-if="props.cell_value" slot="roles" slot-scope="props">
              <div v-if="props.cell_value.length > 0">
                <div
                  v-for="role in props.cell_value"
                  :key="role.id"
                  class="badge badge-primary mr-1"
                >{{ role.name }}</div>
              </div>
              <div v-else></div>
            </template>
            <template slot="created_at" slot-scope="props">{{ props.cell_value | DateWithTime }}</template>
            <template slot="action-buttons" slot-scope="props">
              <a href="#" @click="editModal(props.row)" class="btn btn-primary btn-xs">
                <i class="fas fa-edit fa-fw"></i>
              </a>
              <a href="#" @click="deleteUser(props.row.id)" class="btn btn-danger btn-xs">
                <i class="fas fa-trash fa-fw"></i>
              </a>
            </template>
          </vue-bootstrap4-table>
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
            @submit.prevent="editMode ? editUser(form) : createUser(form)"
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
import userApi from "../../api/users";
import roleApi from "../../api/roles";
import VueBootstrap4Table from "vue-bootstrap4-table";
import { mapGetters } from "vuex";

export default {
  data() {
    return {
      editMode: false,
      form: new Form({
        id: "",
        name: "",
        email: "",
        password: "",
        roles: "",
        type: ""
      }),
      roles: [],
      types: ["regular-user", "client"],
      columns: [
        {
          label: "title",
          name: "name",
          filter: {
            type: "simple",
            placeholder: "Enter username"
          },
          sort: true,
          row_text_alignment: "text-left"
        },
        {
          label: "email",
          name: "email",
          filter: {
            type: "simple"
          },
          sort: true
        },
        {
          label: "Role",
          name: "roles",
          filter: {
            type: "simple"
          },
          sort: true
        },
        {
          label: "user type",
          name: "type",
          filter: {
            type: "simple"
          },
          sort: true
        },
        {
          label: "created at",
          name: "created_at",
          sort: true
        },
        {
          label: "action",
          name: "action-buttons"
        }
      ],
      config: {
        server_mode: true,
        card_mode: false,
        show_refresh_button: false,
        pagination: true,
        pagination_info: true,
        per_page: 15
      },
      classes: {
        table: {
          "table-sm": true
        }
      },
      queryParams: {
        sort: [],
        filters: [],
        global_search: "",
        per_page: 15,
        page: 1
      },
      total_rows: 0
    };
  },
  methods: {
    onChangeQuery(queryParams) {
      this.queryParams = queryParams;
      this.getUsers();
    },
    getUsers() {
      this.$Progress.start();
      this.$store
        .dispatch("user/getUsers", {
          queryParams: this.queryParams,
          page: this.queryParams.page
        })
        .then(response => {
          this.total_rows = response.data.data.total;
          this.$Progress.finish();
        })
        .catch(error => {
          this.$Progress.fail();
          console.log(error);
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
    createUser(form) {
      this.$Progress.start();
      this.$store
        .dispatch("user/createUser", form)
        .then(response => {
          this.$Progress.finish();
          $("#Modal").modal("hide");
          Toast.fire({
            type: "success",
            title: response.data.message
          });
        })
        .catch(error => {
          this.$Progress.fail();
          if (error.response) {
            this.form.errors.errors = error.response.data.errors;
          }
        });
    },
    editUser(user) {
      this.$Progress.start();
      this.$store
        .dispatch("user/editUser", user)
        .then(response => {
          $("#Modal").modal("hide");
          this.$Progress.finish();
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
          if (error.response) {
            this.form.errors.errors = error.response.data.errors;
          }
        });
    },
    getAllRoles() {
      roleApi
        .getAll()
        .then(response => {
          this.roles = _.map(response.data.data, function(key, value) {
            return { id: key.id, name: key.name };
          });
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
          userApi
            .delete(id)
            .then(response => {
              this.$Progress.finish();
              this.onChangeQuery(this.queryParams);
              Toast.fire({
                type: "success",
                title: response.data.message
              });
            })
            .catch(error => {
              console.log(error);
              this.$Progress.fail();
              Toast.fire({
                type: "error",
                title: error.response.data.message
              });
            });
        }
      });
    }
  },
  mounted() {
    this.getUsers();
    this.getAllRoles();
  },
  computed: {
    ...mapGetters({
      users: "user/activeUsers"
    }),
    resultUsers() {
      return this.users.data;
    }
  },

  components: {
    VueBootstrap4Table
  }
};
</script>