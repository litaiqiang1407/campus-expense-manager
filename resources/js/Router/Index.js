import { createRouter, createWebHistory } from "vue-router";

// Chỉ cần một lần import
import { Home, NotFound, Welcome, Signup, Signin, Account, Transaction } from "../Pages/Index";

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
        path: '/transaction',
        name: 'Transaction',
        component: Transaction
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
