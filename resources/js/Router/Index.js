import { createRouter, createWebHistory } from "vue-router";

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
        component: Transaction,
        meta: { layout: MenuLayout },
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
        component: Account,
        meta: { layout: DefaultLayout, title: 'Account', isBack: false }, 
        props: (route) => ({ title: route.meta.title, isBack: route.meta.isBack }), 
    },
]

const router = createRouter({
    history: createWebHistory(),
    routes
})

export default router;
