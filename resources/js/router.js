import Vue from 'vue';
import VueRouter from 'vue-router';

Vue.use(VueRouter);


import login from './components/loginComponent'
import admin from './components/AdminComponent'
import register from './components/RegisterComponent'
import roles from './components/RolesComponent'


const routes = [
    {
        path : '/',
        redirect : '/login',
        
    },
    {
        path : '/login',
        component : login,
        beforeEnter : (to, from, next ) => {
            if( localStorage.getItem('token') ){
                next('/admin');
            }else{
                next();
            }
        }
    },
    {
        path : '/register',
        component : register
    },
    {
        path : '/admin',
        component : admin,
        children : [
            {
                path : 'roles',
                component : roles,
                name : 'roles',
            }
        ],
        beforeEnter : (to, from, next ) => {
            if( localStorage.getItem('token') ){
                next();
            }else{
                next('/login');
            }
        }
    },

];
export default new VueRouter({
    routes
});


