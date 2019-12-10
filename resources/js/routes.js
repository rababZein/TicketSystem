/*
|-------------------------------------------------------------------------------
| routes.js
|-------------------------------------------------------------------------------
| Contains all of the routes for the application
*/

/*
    Imports Vue and VueRouter to extend with the routes.
*/
import Vue from "vue";
import VueRouter from "vue-router";

Vue.use(VueRouter);

// routes
let routes = [
    {
        path: "/admin",
        component: require("./pages/DashboardPage/DashboardComponent.vue")
            .default,
        alias: "",
        meta: { title: "Dashboard" }
    },
    {
        path: "/admin/dashboard",
        component: require("./pages/DashboardPage/DashboardComponent.vue")
            .default,
        meta: { title: "Dashboard" }
    },
    {
        path: "/admin/clients",
        component: require("./pages/ClientPage/ClientsComponent.vue").default,
        meta: { title: "Users" }
    },
    {
        path: "/admin/users",
        component: require("./pages/UserPage/UsersComponent.vue").default,
        meta: { title: "Users" }
    },
    {
        path: "/admin/profile/:id",
        component: require("./pages/UserPage/profileComponent.vue").default,
        meta: { title: "Users" }
    },
    {
        path: "/admin/projects/:page?",
        name: "projects.list",
        component: require("./pages/ProjectPage/ProjectsComponent.vue").default,
        meta: { title: "Projects" }
    },
    {
        path: "/admin/project/:id",
        component: require("./pages/ProjectPage/SingleProjectComponent.vue")
            .default,
        meta: { title: "Project" }
    },
    {
        path: "/admin/tickets/:page?",
        name: "tickets.list",
        component: require("./pages/TicketPage/TicketsComponent.vue").default,
        meta: { title: "Tickets" }
    },
    {
        path: "/admin/ticket/:id",
        component: require("./pages/TicketPage/SingleTicketComponent.vue")
            .default,
        meta: { title: "Ticket" }
    },
    {
        path: "/admin/tasks/:page?",
        name: "tasks.list",
        component: require("./pages/TaskPage/TasksComponent.vue").default,
        meta: { title: "Tasks" }
    },
    {
        path: "/admin/task/:id",
        component: require("./pages/TaskPage/SingleTaskComponent.vue").default,
        meta: { title: "Task" }
    },
    {
        path: "/admin/board/:projectId",
        name: "board",
        component: require("./pages/TaskPage/SingleTaskComponent.vue").default,
    },
    {
        path: "/admin/receipts",
        component: require("./pages/ProjectPage/BoardComponent.vue").default,
        meta: { title: "Receipts" }
    },
    {
        alias: "/admin/time-report",
        path: "/admin/time-report",
        name: "timeReport.list",
        component: require("./pages/TimeReportingPage/TimeReportingComponent.vue")
            .default,
        meta: { title: "Time Report" }
    },
    {
        path: "/admin/permissions",
        component: require("./pages/PermissionPage/PermissionsComponent.vue")
            .default,
        meta: { title: "Permissions" }
    },
    {
        path: "/admin/roles",
        component: require("./pages/RolePage/RolesComponent.vue").default,
        meta: { title: "Roles" }
    },
    {
        path: "/404",
        component: require("./pages/PartialPage/NotFound.vue").default
    },
    {
        path: "*",
        redirect: "/404"
    }
];

export default new VueRouter({
    mode: "history",
    routes,
    linkExactActiveClass: "active"
});
