/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
require('admin-lte');

window.Vue = require('vue');
import VueRouter from 'vue-router'
import { Form, HasError, AlertError } from 'vform'
import moment from 'moment';
import VueProgressBar from 'vue-progressbar'
import Swal from 'sweetalert2'
import api from './api'


Vue.use(VueRouter)
window.Form = Form;
Vue.component(HasError.name, HasError)
Vue.component(AlertError.name, AlertError)
window.Swal = Swal;
Vue.component('pagination', require('laravel-vue-pagination'));
// all components will inherit that property as this.$api.
Vue.prototype.$api = api


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

// progressbar
Vue.use(VueProgressBar, {
    color: 'rgb(143, 255, 199)',
    failedColor: 'red',
    height: '2px'
})

// global filters
Vue.filter('myDate', function (createAt) {
    return moment(createAt).format(' DD/MM/YYYY');
})

// sweetalert
const Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000
})

window.Toast = Toast;

const router = new VueRouter({
    mode: 'history',
    routes, // short for `routes: routes`
    linkExactActiveClass: 'active',
})

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i);
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default));

// Vue.component('example-component', require('./components/ExampleComponent.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
    router,
});
