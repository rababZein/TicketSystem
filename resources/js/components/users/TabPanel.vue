<template>
  <div class="col-md-9">
    <div class="card">
      <div class="card-header p-2">
        <ul class="nav nav-pills">
          <li class="nav-item" v-for="tab in tabs" :key="tab.component" >
            <div class="nav-link" :class="{ active: selected === tab.component }" @click="selected = tab.component;">{{ tab.title }}</div>
          </li>
        </ul>
      </div>
      <div class="card-body p-0">
        <div class="tab-content">
          <component :is="selected" v-bind="{ userId: this.user.id }"></component>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { mapGetters } from "vuex";

export default {
  data() {
    return {
      tabs: [
            {component: "ActivityList", title: "activity"},
            {component: "ProjectsListPerUser",  title: "projects"},
            {component: "TicketsListPerUser",  title: "tickets"},
            {component: "TasksListPerUser",  title: "tasks"},
            {component: "UserComment",  title: "comments"}
      ],
      selected: "ActivityList"
    };
  },
  computed: {
    ...mapGetters({
      user: "user/activeSingleUser"
    })
  }
};
</script>

<style scoped>
.nav-link {
    cursor: pointer;
}
</style>