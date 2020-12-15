import Vue from "vue";
import VueRouter from "vue-router";

Vue.use(VueRouter);

import login from "./components/loginComponent";
import admin from "./components/AdminComponent";
import register from "./components/RegisterComponent";
import roles from "./components/RolesComponent";
import crud from './components/CrudComponent';
<<<<<<< HEAD
import user from './components/userComponent';
=======
import users from './components/UserComponent';
>>>>>>> 25e82745305c70409f467a0bae9a65467d87dbc9

const routes = [
    {
        path: "/",
        redirect: "/login"
    },
    {
        path: "/login",
        component: login,
        beforeEnter: (to, from, next) => {
            if (localStorage.getItem("token")) {
                next("/admin");
            } else {
                next();
            }
        }
    },
    {
        path: "/register",
        component: register
    },
    {
        path: "/admin",
        component: admin,
        name : "admin",
        children: [
            {
                path: "roles",
                component: roles,
                name: "roles"
            },
            {
                path : "user",
                component : user,
                name : "user"
            },
            {
                path : 'crud',
                component : crud,
                name : 'crud',
            },
            {
                path: 'users',
                component: users,
                name: 'users',
            }
        ],
        beforeEnter: (to, from, next) => {
            axios.get('api/verify')
            .then( res => next())
            .catch( err => next('/login') ) 
        }
    }
];

const router = new VueRouter({ routes });

router.beforeEach((to, from, next) => {
    const token = localStorage.getItem("token") || null;
    window.axios.defaults.headers["Authorization"] = "Bearer " + token;
    next();
});

export default router;
