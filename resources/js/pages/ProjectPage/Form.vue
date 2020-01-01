<template>
  <div class="row justify-content-center">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <div class="card-title font-weight-light">Edit Project</div>
        </div>
        <form @submit.prevent="editProject(form.id)" @keydown="form.onKeydown($event)">
          <div class="card-body">
            <div class="row">
              <div class="form-group col-sm-12 col-md-3">
                <label for="name">name</label>
                <input
                  v-model="form.name"
                  type="text"
                  name="name"
                  class="form-control"
                  :class="{ 'is-invalid': form.errors.has('name') }"
                />
                <has-error :form="form" field="name"></has-error>
              </div>
              <div class="form-group col-sm-12 col-md-3">
                <label for="description">description</label>
                <textarea
                  v-model="form.description"
                  type="text"
                  name="description"
                  class="form-control"
                  :class="{ 'is-invalid': form.errors.has('description') }"
                ></textarea>
                <has-error :form="form" field="description"></has-error>
              </div>
              <div class="form-group col-sm-12 col-md-3">
                <label for="client">Client</label>
                <multiselect
                  v-model="form.owner"
                  :options="owners"
                  :searchable="true"
                  :close-on-select="true"
                  :clear-on-select="false"
                  :preserve-search="true"
                  placeholder="Select one"
                  label="name"
                  track-by="name"
                  :preselect-first="true"
                  @input="opt => form.owner_id = opt.id"
                ></multiselect>
                <has-error :form="form" field="owner_id"></has-error>
              </div>
              <div class="form-group col-sm-12 col-md-3">
                <label for="name">Assigned Users</label>
                <multiselect
                  v-model="form.project_assign"
                  :options="responsible"
                  :multiple="true"
                  :close-on-select="false"
                  :clear-on-select="false"
                  :preserve-search="true"
                  placeholder="Pick some"
                  label="name"
                  track-by="id"
                  :preselect-first="true"
                >
                  <template slot="selection" slot-scope="{ values, search, isOpen }">
                    <span
                      class="multiselect__single"
                      v-if="values.length &amp;&amp; !isOpen"
                    >{{ values.length }} options selected</span>
                  </template>
                </multiselect>
                <has-error :form="form" field="project_assign"></has-error>
              </div>
              <div class="form-group col-sm-12 col-md-3">
                <label for="task_rate">task rate</label>
                <input
                  v-model="form.task_rate"
                  type="number"
                  min="0"
                  step="0.01"
                  name="task_rate"
                  class="form-control"
                  :class="{ 'is-invalid': form.errors.has('task_rate') }"
                />
                <has-error :form="form" field="task_rate"></has-error>
              </div>
              <div class="form-group col-sm-12 col-md-3">
                <label for="budget_hours">budget hours</label>
                <input
                  v-model="form.budget_hours"
                  type="number"
                  min="0"
                  step="0.01"
                  name="budget_hours"
                  class="form-control"
                  :class="{ 'is-invalid': form.errors.has('budget_hours') }"
                />
                <has-error :form="form" field="budget_hours"></has-error>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      form: new Form({
        id: "",
        name: "",
        owner: "",
        description: "",
        task_rate: "",
        budget_hours: "",
        project_assign: []
      })
    };
  }
};
</script>

<style>
</style>