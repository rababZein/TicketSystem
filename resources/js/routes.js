/*
|-------------------------------------------------------------------------------
| routes.js
|-------------------------------------------------------------------------------
| Contains all of the routes for the application
*/

/*
    Imports Vue and VueRouter to extend with the routes.
*/
import Vue from 'vue'
import VueRouter from 'vue-router'

Vue.use( VueRouter )

// routes
let routes = [
    {
        path: '/home',
        component: require('./components/DashboardComponent.vue').default,
        alias: '',
        meta: { title: 'Dashboard' }
    },
    {
        path: '/dashboard',
        component: require('./components/DashboardComponent.vue').default,
        meta: { title: 'Dashboard' }
    },
    {
        path: '/users',
        component: require('./components/UsersComponent.vue').default,
        meta: { title: 'Users' }
    },
    {
        path: '/projects',
        component: require('./components/ProjectsComponent.vue').default,
        meta: { title: 'Projects' }
    },
    {
        path: '/tickets',
        component: require('./components/TicketsComponent.vue').default,
        meta: { title: 'Tickets' }
    },
    {
        path: '/tasks',
        component: require('./components/TasksComponent.vue').default,
        meta: { title: 'Tasks' }
    },
    {
        path: '/receipts',
        component: require('./components/ReceiptsComponent.vue').default,
        meta: { title: 'Receipts' }
    },
    {
        path: '/permissions',
        component: require('./components/PermissionsComponent.vue').default,
        meta: { title: 'Permissions' }
    },
    {
        path: '/roles',
        component: require('./components/RolesComponent.vue').default,
        meta: { title: 'Roles' }
    },
    {
        path: '*',
        component: require('./components/DashboardComponent.vue').default
    }
]

export default new VueRouter({
    mode: 'history',
    routes,
    linkExactActiveClass: 'active',
});