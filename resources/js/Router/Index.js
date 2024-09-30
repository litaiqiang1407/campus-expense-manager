import { createRouter, createWebHistory } from "vue-router";

import { Home, NotFound, Welcome, Signup, AppInfo } from "../Pages/Index";

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
        path: '/app-info',
        name: 'AppInfo',
        component: AppInfo  
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