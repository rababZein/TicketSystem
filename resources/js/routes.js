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
        path: '*',
        component: require('./components/UsersComponent.vue').default
    }
]

export default new VueRouter({
    mode: 'history',
    routes,
    linkExactActiveClass: 'active',
});