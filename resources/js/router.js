import Vue from "vue";
import VueRouter from "vue-router";

Vue.use(VueRouter);

import login from "./components/loginComponent";
import admin from "./components/AdminComponent";
import register from "./components/RegisterComponent";
import roles from "./components/RolesComponent";
import crud from './components/CrudComponent';

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
                path : 'crud',
                component : crud,
                name : 'crud',
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
