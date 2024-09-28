import { createRouter, createWebHistory } from "vue-router";

import { Home, NotFound, Welcome, Signup, Signin, Account } from "../Pages/Index";

const routes = [
    {
        path: '/',
        name: 'Home',
        component: Home
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
    {
        path: '/account',
        name: 'Account',
        component: Account
    },
    
]

const router = createRouter({
    history: createWebHistory(),
    routes
})

export default router;