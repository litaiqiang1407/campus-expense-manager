import { createRouter, createWebHistory } from "vue-router";

// Import pages
import { Home, NotFound, Welcome, Signup, Signin, Account, Transaction, MyAccount} from "../Pages/Index";

// Import layout components
import { MenuLayout, HeaderLayout, DefaultLayout } from "../Components/Layout/Index";

import { Support, Menu } from "../Components/Header/Components/Index";

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
        path: '/myaccount',
        name: 'MyAccount',
        component: MyAccount,
        meta: { layout: DefaultLayout, title: 'My Account', isBack: true}, 
        
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes
});

export default router;
