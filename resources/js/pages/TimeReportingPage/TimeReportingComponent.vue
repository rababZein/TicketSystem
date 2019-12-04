<template>
  <div class="row">
    <div class="col-sm-12">
      <div class="row">
        <div class="col-sm-12 col-md-4">
          <label for="dateRange">Date Range</label>
          <date-picker
            v-model="dateRange"
            type="date"
            value-type="date"
            lang="en"
            format="YYYY-MM-DD"
            range
            input-class="form-control"
            @input="dateRangeChange(dateRange)"
          ></date-picker>
        </div>
        <div class="col-sm-12 col-md-4 form-group">
          <label for="employee">Employee</label>
          <multiselect
            v-model="selectedEmployee"
            :options="employee"
            placeholder="Select employee"
            label="name"
            :allow-empty="true"
            :show-labels="false"
            @select="(opt) => {employeeChange(opt)}"
            @remove="() => {employeeChange('')}"
          ></multiselect>
        </div>
        <div class="col-sm-12 col-md-4 form-group">
          <label for="name">Project</label>
          <multiselect
            v-model="selectedProject"
            :options="projects"
            placeholder="Select Project"
            label="name"
            @select="(opt) => {projectChange(opt.id)}"
            @remove="() => {projectChange(id = '')}"
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
        @pagination-change-page="onPaginate"
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
      data: {
        from_date: moment(this.$route.query.from_date).format("YYYY-MM-DD"),
        to_date: moment(this.$route.query.to_date).format("YYYY-MM-DD"),
        employee_id: this.$route.query.employee_id || "",
        project_id: this.$route.query.project_id || "",
        page: 1
      },
      dateRange: [],
    };
  },
  components: { DatePicker },
  methods: {
    // pagination
    onPaginate(page) {
      this.data.page = page;
      // get time report list
      this.reporting(this.data);
      this.$router.push({
        name: "timeReport.list",
        query: { ...this.data }
      });
    },
    // get time report list
    reporting(form) {
      this.$Progress.start();
      this.$store
        .dispatch("track/reporting", { form })
        .then(response => {
          this.$router.push({
            name: "timeReport.list",
            query: { ...form }
          });
          this.$Progress.finish();
        })
        .catch(error => {
          console.log(error);
          this.$Progress.fail();
        });
    },
    // on date range change
    dateRangeChange(opt) {
      this.data.from_date = moment(opt[0]).format("YYYY-MM-DD");
      this.data.to_date = moment(opt[1]).format("YYYY-MM-DD");
      this.dateRange = [this.data.from_date , this.data.to_date];
      // get time report list
      this.reporting(this.data);
      this.$router.push({
        name: "timeReport.list",
        query: { ...this.data }
      });
    },
    // on employee change
    employeeChange(opt) {
      this.data.employee_id = opt.id;
      // get time report list
      this.reporting(this.data);
      this.$router.push({
        name: "timeReport.list",
        query: { ...this.data }
      });
    },
    // on project change
    projectChange(id) {
      this.data.project_id = id;
      // get time report list
      this.reporting(this.data);
      this.$router.push({
        name: "timeReport.list",
        query: { ...this.data }
      });
    }
  },
  mounted() {    
    // get time report list
    this.reporting(this.data);

    // get all employee
    this.$store.dispatch("regularUser/getRegularUser").catch(error => {
      console.log(error);
    });
    // get all projects without pagination
    this.$store.dispatch("project/getAllProjects").catch(error => {
      console.log(error);
    });
  },
  computed: {
    ...mapGetters({
      time_counting: "track/activeTimeReport",
      employee: "regularUser/activeRegularUser",
      projects: "project/allProjects"
    }),
    selectedEmployee() {
      return this.employee.find(item => item.id == this.$route.query.employee_id);
    },
    selectedProject() {
      return this.projects.find(item => item.id == this.$route.query.project_id);
    }
  }
};
</script>

<style scoped>
.mx-datepicker {
  display: block;
  width: unset;
}
</style>