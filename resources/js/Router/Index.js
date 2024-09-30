import { createRouter, createWebHistory } from "vue-router";

import { Home, NotFound, Welcome, Signup, Signin } from "../Pages/Index";

import { MenuLayout, HeaderLayout, DefaultLayout } from "../Components/Layout/Index";

const routes = [
    {
        path: '/',
        name: 'Home',
        component: Home,
        meta: { layout: MenuLayout },
    },
    {
        path: '/welcome',
        name: 'Welcome',
        component: Welcome
    },
    {
        path: '/signup',
        name: 'Signup',
        component: Signup
    },
    {
        path: '/signin',
        name: 'Signin',
        component: Signin
    },
    {
        path: '/:pathMatch(.*)*',
        name: 'NotFound',
        component: NotFound
    },
    
]

const router = createRouter({
    history: createWebHistory(),
    routes
})

export default router;