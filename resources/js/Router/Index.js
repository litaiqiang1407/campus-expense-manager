import { createRouter, createWebHistory } from "vue-router";

// Import pages
import { Home, NotFound, Welcome, Signup, Signin, Account, Transaction, Notification, Budget, CreateTransaction, CreateBudget, MyWallet, AppInfo } from "../Pages/Index";

// Import layout components
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
        path: '/app-info',
        name: 'AppInfo',
        component: AppInfo,
        meta: { layout: HeaderLayout, title: '', isBack: true, isCancel: false},
        props: (route) => ({ title: route.meta.title, isBack: route.meta.isBack, isCancel: route.meta.isCancel }),
    },
    {  
        path: '/transaction',
        name: 'Transaction',
        component: Transaction,
        meta: { layout: MenuLayout },
    },
    {
        path: '/transaction/create',
        name: 'CreateTransaction',
        component: CreateTransaction,
        meta: { layout: HeaderLayout, title: 'Add transaction', isBack: false, isCancel: true},
        props: (route) => ({ title: route.meta.title, isBack: route.meta.isBack, isCancel: route.meta.isCancel }),
    },
    {
        path: '/signin',
        name: 'login',
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
        meta: { layout: DefaultLayout, title: 'Account', isBack: false, isCancel: false, headerComponent: [Support]},
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
    {
        path: '/budget/create',
        name: 'CreateBudget',
        component: CreateBudget,
        meta: { layout: HeaderLayout, title: 'Add budget', isBack: false, isCancel: true},
        props: (route) => ({ title: route.meta.title, isBack: route.meta.isBack, isCancel: route.meta.isCancel }),
    },
    {
        path: '/my-wallet',
        name: 'MyWallet',
        component: MyWallet,
        meta: { layout: HeaderLayout, title: 'My Wallet', isBack: true, isCancel: false},
        props: (route) => ({ title: route.meta.title, isBack: route.meta.isBack, isCancel: route.meta.isCancel }),
    },
    {
        path: '/logout',
        name: 'Logout',        
    }

]

const router = createRouter({
    history: createWebHistory(),
    routes
});

export default router;
