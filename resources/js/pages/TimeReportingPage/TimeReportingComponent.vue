<template>
  <div class="row">
    <div class="col-sm-12">
      <div class="row">
        <div class="col-sm-12 col-md-4">
          <label for="dateRange">Date Range</label>
          <date-picker
            v-model="dateRange"
            type="type"
            value-type="date"
            lang="en"
            format="YYYY-MM-DD"
            range
            input-class="form-control"
            @input="dateRangeChange(dateRange)"
          ></date-picker>
          <has-error :form="form" field="from_date"></has-error>
        </div>
        <div class="col-sm-12 col-md-4 form-group">
          <label for="employee">Employee</label>
          <multiselect
            v-model="selectedEmployee"
            :options="employee"
            placeholder="Select employee"
            label="name"
            track-by="name"
            :allow-empty="true"
            :show-labels="false"
            @select="opt => {form.employee_id = opt.id; this.reporting();}"
            @remove="() => {form.employee_id = ''; this.reporting();}"
          ></multiselect>
        </div>
        <div class="col-sm-12 col-md-4 form-group">
          <label for="name">Project</label>
          <multiselect
            v-model="selectedProject"
            :options="projects"
            :close-on-select="true"
            :clear-on-select="false"
            :preserve-search="true"
            placeholder="Select Project"
            label="name"
            @select="opt => {form.project_id = opt.id; this.reporting();}"
            @remove="() => {form.project_id = ''; this.reporting();}"
          ></multiselect>
        </div>
      </div>

      <time-report-table :time_counting="time_counting"></time-report-table>
      <pagination
        align="right"
        size="small"
        :show-disabled="true"
        :data="time_counting"
        :limit="3"
        @pagination-change-page="reporting"
      ></pagination>
    </div>
  </div>
</template>

<script>
import DatePicker from "vue2-datepicker";
import moment from "moment";
import { mapGetters } from "vuex";

export default {
  data() {
    return {
      form: new Form({
        from_date: moment()
          .startOf("month")
          .format("YYYY-MM-DD"),
        to_date: moment().format("YYYY-MM-DD"),
        employee_id: "",
        project_id: ""
      }),
      selectedEmployee: "",
      selectedProject: "",
      dateRange: [
        moment()
          .startOf("month")
          .format("YYYY-MM-DD"),
        moment().format("YYYY-MM-DD")
      ]
    };
  },
  components: { DatePicker },
  methods: {
    reporting(page = 1) {
      this.$Progress.start();
      this.$store
        .dispatch("track/reporting", {
          from_date: this.form.from_date,
          to_date: this.form.to_date,
          employee_id: this.form.employee_id,
          project_id: this.form.project_id,
          page: page
        })
        .then(response => {
          this.form.clear();
          this.$Progress.finish();
        })
        .catch(error => {
          if (error.response) {
            this.form.errors.errors = error.response.data.errors;
          }
          this.$Progress.fail();
        });
    },
    dateRangeChange(opt) {
      this.form.from_date = moment(opt[0]).format("YYYY-MM-DD");
      this.form.to_date = moment(opt[1]).format("YYYY-MM-DD");
      this.reporting();
    }
  },
  mounted() {
    this.reporting();
    this.$store.dispatch("regularUser/getRegularUser").catch(error => {
      console.log(error);
    });
    this.$store.dispatch("project/getAllProjects").catch(error => {
      console.log(error);
    });
  },
  computed: {
    ...mapGetters({
      time_counting: "track/activeTimeReport",
      employee: "regularUser/activeRegularUser",
      projects: "project/allProjects"
    })
  }
};
</script>

<style scoped>
.mx-datepicker {
  display: block;
  width: unset;
}
</style>