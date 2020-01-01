<template>
  <div class="row justify-content-center">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <div class="card-title font-weight-light">Account Settings</div>
        </div>
        <form @submit.prevent="editUser(form)" @keydown="form.onKeydown($event)" autocomplete="off">
          <div class="card-body">
            <div class="row">
              <div class="form-group col-sm-12 col-md-3">
                <label for="name">Username</label>
                <input
                  v-model="form.name"
                  type="text"
                  name="name"
                  class="form-control"
                  :class="{ 'is-invalid': form.errors.has('name') }"
                  autocomplete="off"
                />
                <has-error :form="form" field="name"></has-error>
              </div>
              <div class="form-group col-sm-12 col-md-3">
                <label for="email">Email</label>
                <input
                  v-model="form.email"
                  type="email"
                  name="email"
                  class="form-control"
                  :class="{ 'is-invalid': form.errors.has('email') }"
                  autocomplete="off"
                />
                <has-error :form="form" field="email"></has-error>
              </div>
              <div class="form-group col-sm-12 col-md-3">
                <label for="password">Password</label>
                <input
                  v-model="form.password"
                  type="password"
                  name="password"
                  class="form-control"
                  :class="{ 'is-invalid': form.errors.has('password') }"
                  autocomplete="off"
                />
                <has-error :form="form" field="password"></has-error>
              </div>
              <div class="form-group col-sm-12 col-md-3">
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
            </div>
            <div class="row">
              <div class="form-group col-sm-12 col-md-3">
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
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-success">Update</button>
          </div>
        </form>
      </div>
      <div class="card">
        <div class="card-header">
          <div class="card-title font-weight-light">Account Details</div>
        </div>
        <form
          @submit.prevent="editMode ? editMetadata(metadata) : createMetadata(metadata)"
          @keydown="form.onKeydown($event)"
          autocomplete="off"
        >
          <div class="card-body">
            <div class="row">
              <div class="form-group col-sm-12 col-md-3">
                <label for="first_name">First Name</label>
                <input
                  v-model="metadata.first_name"
                  type="text"
                  name="first_name"
                  class="form-control"
                  :class="{ 'is-invalid': form.errors.has('first_name') }"
                  autocomplete="off"
                />
                <has-error :form="form" field="first_name"></has-error>
              </div>
              <div class="form-group col-sm-12 col-md-3">
                <label for="last_name">last name</label>
                <input
                  v-model="metadata.last_name"
                  type="text"
                  name="last_name"
                  class="form-control"
                  :class="{ 'is-invalid': form.errors.has('last_name') }"
                  autocomplete="off"
                />
                <has-error :form="form" field="last_name"></has-error>
              </div>
              <div class="form-group col-sm-12 col-md-3">
                <label for="company">Company name</label>
                <input
                  v-model="metadata.company"
                  type="text"
                  name="company"
                  class="form-control"
                  :class="{ 'is-invalid': form.errors.has('company') }"
                  autocomplete="off"
                />
                <has-error :form="form" field="company"></has-error>
              </div>
              <div class="form-group col-sm-12 col-md-3">
                <label for="address">Address</label>
                <input
                  v-model="metadata.address"
                  type="text"
                  name="address"
                  class="form-control"
                  :class="{ 'is-invalid': form.errors.has('address') }"
                  autocomplete="off"
                />
                <has-error :form="form" field="address"></has-error>
              </div>
              <div class="form-group col-sm-12 col-md-3">
                <label for="language">Language</label>
                <multiselect
                  v-model="metadata.language"
                  :options="language"
                  :searchable="false"
                  :close-on-select="true"
                  :show-labels="false"
                  placeholder="Pick a value"
                ></multiselect>
                <has-error :form="form" field="language"></has-error>
              </div>
              <div class="form-group col-sm-12 col-md-3">
                <label for="gender">Gender</label>
                <multiselect
                  v-model="metadata.gender"
                  :options="gender"
                  :searchable="false"
                  :close-on-select="true"
                  :show-labels="false"
                  placeholder="Pick a value"
                ></multiselect>
                <has-error :form="form" field="gender"></has-error>
              </div>
              <div class="form-group col-sm-12 col-md-3">
                <label for="telephone">Telephone</label>
                <input
                  v-model="metadata.telephone"
                  type="text"
                  name="telephone"
                  class="form-control"
                  :class="{ 'is-invalid': form.errors.has('telephone') }"
                  autocomplete="off"
                />
                <has-error :form="form" field="telephone"></has-error>
              </div>
              <div class="form-group col-sm-12 col-md-3">
                <label for="mobile">Mobile</label>
                <input
                  v-model="metadata.mobile"
                  type="text"
                  name="mobile"
                  class="form-control"
                  :class="{ 'is-invalid': form.errors.has('mobile') }"
                  autocomplete="off"
                />
                <has-error :form="form" field="mobile"></has-error>
              </div>
              <div class="form-group col-sm-12 col-md-3">
                <label for="fax">Fax</label>
                <input
                  v-model="metadata.fax"
                  type="text"
                  name="fax"
                  class="form-control"
                  :class="{ 'is-invalid': form.errors.has('fax') }"
                  autocomplete="off"
                />
                <has-error :form="form" field="fax"></has-error>
              </div>
              <div class="form-group col-sm-12 col-md-3">
                <label for="website">Website</label>
                <input
                  v-model="metadata.website"
                  type="text"
                  name="website"
                  class="form-control"
                  :class="{ 'is-invalid': form.errors.has('website') }"
                  autocomplete="off"
                />
                <has-error :form="form" field="website"></has-error>
              </div>
              <div class="form-group col-sm-12 col-md-3">
                <label for="birth_date">Birth Date</label>
                <date-picker
                  v-model="metadata.birth_date"
                  lang="en"
                  type="date"
                  format="YYYY-MM-DD"
                  :minute-step="1"
                  value-type="format"
                  input-class="form-control"
                ></date-picker>
                <has-error :form="form" field="birth_date"></has-error>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-success">Update</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
import roleApi from "../../api/roles";
import DatePicker from "vue2-datepicker";
import { mapActions, mapGetters } from "vuex";

export default {
  components: { DatePicker },
  data() {
    return {
      editMode: true,
      userId: this.$route.params.id,
      form: new Form({
        id: "",
        name: "",
        email: "",
        password: "",
        roles: "",
        type: ""
      }),
      metadata: new Form({
        user_id: this.$route.params.id,
        id: "",
        first_name: "",
        last_name: "",
        company: "",
        address: "",
        language: "",
        gender: "",
        telephone: "",
        mobile: "",
        fax: "",
        website: "",
        birth_date: ""
      }),
      roles: [],
      types: ["regular-user", "client"],
      language: ["de", "en"],
      gender: ["male", "female"]
    };
  },
  methods: {
    ...mapActions("user", ["getUserById"]),
    editUser(data) {
      this.$Progress.start();
      this.$store
        .dispatch("user/editUser", data)
        .then(response => {
          this.$Progress.finish();
          Toast.fire({
            type: "success",
            title: response.data.message
          });
          this.$router.push({
            name: "profile",
            params: { id: this.userId }
          });
        })
        .catch(error => {
          console.log(error);
          this.$Progress.fail();
          if (error.response) {
            this.form.errors.errors = error.response.data.errors;
          }
        });
    },
    createMetadata(data) {
      this.$Progress.start();
      this.$store
        .dispatch("user/createMetadata", data)
        .then(response => {
          this.$Progress.finish();
          Toast.fire({
            type: "success",
            title: response.data.message
          });
          this.$router.push({
            name: "profile",
            params: { id: this.userId }
          });
        })
        .catch(error => {
          console.log(error);
          this.$Progress.fail();
          if (error.response) {
            this.form.errors.errors = error.response.data.errors;
          }
        });
    },
    editMetadata(data) {
      this.$Progress.start();
      this.$store
        .dispatch("user/editMetadata", data)
        .then(response => {
          this.$Progress.finish();
          Toast.fire({
            type: "success",
            title: response.data.message
          });
          this.$router.push({
            name: "profile",
            params: { id: this.userId }
          });
        })
        .catch(error => {
          console.log(error);
          this.$Progress.fail();
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
    async loadEditData() {
      this.$Progress.start();
      let response = await this.getUserById(this.userId)
        .then(() => {
          this.$Progress.finish();
          this.loading = false;
          this.form.fill(this.user);
          if (this.user.metadata !== null) {
            this.metadata.fill(this.user.metadata);
            this.metadata.user_id = this.$route.params.id;
          } else {
            this.editMode = false;
          }
        })
        .catch(error => {
          this.$Progress.fail();
        });
    }
  },
  mounted() {
    this.getAllRoles();
    this.loadEditData();
  },
  computed: {
    ...mapGetters({
      user: "user/activeSingleUser"
    })
  }
};
</script>

<style scoped>
.mx-datepicker {
  display: block;
  width: 100%;
}
</style>