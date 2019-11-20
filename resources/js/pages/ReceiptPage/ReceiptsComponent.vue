<template>
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">Receipts Table</h3>

          <div class="card-tools">
            <button type="submit" class="btn btn-success btn-sm" @click="newModel">
              <i class="fas fa-plus fa-fw"></i>
              <span class="d-none d-lg-inline">New Receipt</span>
            </button>
          </div>
        </div>
        <!-- /.card-header -->
        <div class="card-body table-responsive p-0">
          <table class="table table-hover">
            <thead>
              <tr>
                <th width="10">ID</th>
                <th width="20%">Name</th>
                <th width="40%">Description</th>
                <th width="20%">Task</th>
                <th width="20%">Total</th>
                <th width="10%">Is Paid</th>
                <th>action</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="receipt in receipts" :key="receipt.id">
                <td>{{ receipt.id }}</td>
                <td>{{ receipt.name }}</td>
                <td>{{ receipt.description }}</td>
                <td>{{ receipt.task.name }}</td>
                <td>{{ receipt.total }}</td>
                <td v-if="!receipt.is_paid">Not Paid</td>
                <td v-else>Paid</td>
                <td>
                  <a href="#" @click="editModel(receipt)" class="btn btn-primary btn-xs">
                    <i class="fas fa-edit fa-fw"></i>
                  </a>
                  <a href="#" @click="deleteReceipt(receipt.id)" class="btn btn-danger btn-xs">
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
            :data="receipts"
            :limit="3"
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
      id="newReceipt"
      tabindex="-1"
      role="dialog"
      aria-labelledby="newReceiptLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 v-show="!editMode" class="modal-title" id="newReceiptLabel">Create New Receipt</h5>
            <h5 v-show="editMode" class="modal-title" id="newReceiptLabel">Edit Receipt</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <form
            @submit.prevent="editMode ? editReceipt(form.id) : createReceipt()"
            @keydown="form.onKeydown($event)"
          >
            <div class="modal-body">
              <div class="form-group">
                <label for="name">Receipt Name</label>
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
                <label for="description">Receipt Description</label>
                <input
                  v-model="form.description"
                  type="text"
                  name="description"
                  class="form-control"
                  :class="{ 'is-invalid': form.errors.has('description') }"
                />
                <has-error :form="form" field="description"></has-error>
              </div>
              <div class="form-group">
                <label for="name">Task</label>
                <multiselect
                  v-model="form.task"
                  :options="tasks.data"
                  :close-on-select="true"
                  :clear-on-select="false"
                  :preserve-search="true"
                  placeholder="Select one"
                  label="name"
                  :preselect-first="true"
                  @input="opt => form.task_id = opt.id"
                >
                  <template slot="selection" slot-scope="{ values, search, isOpen }">
                    <span
                      class="multiselect__single"
                      v-if="values.length &amp;&amp; !isOpen"
                    >{{ values.length }} options selected</span>
                  </template>
                </multiselect>
                <has-error :form="form" field="task_id"></has-error>
              </div>
              <div class="form-group">
                <label for="total">Total</label>
                <input
                  v-model="form.total"
                  type="text"
                  name="total"
                  class="form-control"
                  :class="{ 'is-invalid': form.errors.has('total') }"
                />
                <has-error :form="form" field="total"></has-error>
              </div>
              <div class="form-group">
                <div class="custom-control custom-switch">
                  <input
                    v-model="form.is_paid"
                    type="checkbox"
                    name="is_paid"
                    class="custom-control-input"
                    :class="{ 'is-invalid': form.errors.has('is_paid') }"
                    id="is_paid"
                  />
                  <label class="custom-control-label" for="is_paid">Is Paid</label>
                  <has-error :form="form" field="is_paid"></has-error>
                </div>
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
import { mapGetters } from "vuex";
import receiptApi from "../../api/receipts";

export default {
  data() {
    return {
      editMode: false,
      form: new Form({
        id: "",
        name: "",
        description: "",
        total: "",
        is_paid: false,
        task: {
          id: "",
          name: ""
        },
        task_id: ""
      }),
      receipts: {}
    };
  },
  methods: {
    newModel() {
      this.editMode = false;
      this.form.reset();
      $("#newReceipt").modal("show");
    },
    editModel(receipt) {
      this.editMode = true;
      this.form.reset();
      $("#newReceipt").modal("show");
      this.form.fill(receipt);
    },
    getResults(page = 1) {
      this.$Progress.start();
      receiptApi
        .getAll()
        .then(response => {
          this.receipts = response.data.data;

          // convert array to object for paginate
          this.receipts = Object.assign({}, this.receipts);

          this.$Progress.finish();
        })
        .catch(error => {
          this.$Progress.fail();
        });
    },
    getTasks() {
      this.$store
        .dispatch("task/getTasks")
        .then()
        .catch(error => {
          console.log(error);
        })
    },
    createReceipt() {
      this.$Progress.start();

      // need to be enhance
      this.form.task_id = this.form.task.id;

      this.form
        .post("/v-api/receipts/" + this.form.task.project.id)
        .then(response => {
          $("#newReceipt").modal("hide");
          this.$Progress.finish();
          this.getResults();
          Toast.fire({
            type: "success",
            title: "Receipt created successfully"
          });
        })
        .catch(error => {
          this.$Progress.fail();
          Toast.fire({
            type: "error",
            title: "can't create new Receipt"
          });
        });
    },
    editReceipt(id) {
      this.$Progress.start();

      this.form.task_id = this.form.task.id;

      this.form
        .patch("/v-api/receipts/" + id)
        .then(response => {
          $("#newReceipt").modal("hide");
          this.$Progress.finish();
          this.getResults();
          Toast.fire({
            type: "success",
            title: "Receipt updated successfully"
          });
        })
        .catch(error => {
          this.$Progress.fail();
          Toast.fire({
            type: "error",
            title: "can't update the Receipt"
          });
        });
    },
    deleteReceipt(id) {
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
          receiptApi
            .delete(id)
            .then(response => {
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
                title: "can't delete the Receipt"
              });
            });
        }
      });
    }
  },
  mounted() {
    this.getResults();
    this.getTasks();
  },
  computed: {
    ...mapGetters({
      tasks: "task/activeTasks"
    })
  }
};
</script>