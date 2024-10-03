import { createRouter, createWebHistory } from "vue-router";

import { Home, NotFound, Welcome, Signup, Signin, Account, Transaction, Notification, Budget } from "../Pages/Index";

import { MenuLayout, HeaderLayout, DefaultLayout } from "../Components/Layout/Index";

import { Support, Menu, SelectWallet } from "../Components/Header/Components/Index";

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
        meta: { layout: DefaultLayout, title: 'Account', isBack: false, headerComponent: [Support]}, 
        props: (route) => ({ title: route.meta.title, isBack: route.meta.isBack, headerComponent: route.meta.headerComponent }), 
    },
    {
        path: '/notification',
        name: 'Notification',
        component: Notification,
        meta: { layout: HeaderLayout, title: 'Notifications', isBack: false, isCancel: true},
        props: (route) => ({ title: route.meta.title, isBack: route.meta.isBack, isCancel: route.meta.isCancel }), 
    },
    {
        path: '/budget',
        name: 'Budget',
        component: Budget,
        meta: { layout: DefaultLayout, title: 'Budget', isBack: false, headerComponent: [SelectWallet,Menu] }, 
        props: (route) => ({ title: route.meta.title, isBack: route.meta.isBack, headerComponent: route.meta.headerComponent  }), 
    },

]

const router = createRouter({
    history: createWebHistory(),
    routes
})

export default router;
