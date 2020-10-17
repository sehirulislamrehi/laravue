import Vue from 'vue';
import VueRouter from 'vue-router';

Vue.use(VueRouter);


import login from './components/loginComponent'
import admin from './components/AdminComponent'


const routes = [

    {
        path : '/login',
        component : login
    },
    {
        path : '/admin',
        component : admin
    }
];
export default new VueRouter({
    routes
});


