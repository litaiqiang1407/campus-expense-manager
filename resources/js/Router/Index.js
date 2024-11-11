import { createRouter, createWebHistory } from "vue-router";

// Import pages
import { Home, NotFound, Welcome, Signup, Signin, Account, Transaction, Notification, Budget, CreateTransaction, CreateBudget, MyWallet, AppInfo, MyAccount, CreateWallet, EditWallet, Icon, Categories, SelectWallet, WriteNote, AddCategory } from "../Pages/Index";

// Import layout components
import { MenuLayout, HeaderLayout, DefaultLayout, NoneLayout } from "../Components/Layout/Index";

import { Support, Menu, Search, SelectWallet as SelectComponent, SaveButton} from "../Components/Header/Components/Index";

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
    },
    {
        path: '/note',
        name: 'Note',
        component: WriteNote,
        meta: {
            layout: HeaderLayout,
            title: 'Note',
            isBack: false,
            isCancel: true,
            headerComponent: [SaveButton]
        },
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
    },
    {
        path: '/my-account',
        name: 'MyAccount',
        component: MyAccount,
        meta: { layout: DefaultLayout, title: 'My Account', isBack: true},

    },
{
        path: '/notification',
        name: 'Notification',
        component: Notification,
        meta: { layout: HeaderLayout, title: 'Notifications', isBack: false, isCancel: true},
    },
    {
        path: '/budget',
        name: 'Budget',
        component: Budget,
        meta: { layout: MenuLayout },
    },
    {
        path: '/budget/create',
        name: 'CreateBudget',
        component: CreateBudget,
        meta: { layout: HeaderLayout, title: 'Add budget', isBack: false, isCancel: true},
    },
    {
        path: '/categories',
        name: 'Categories',
        component: Categories
    },
    {
        path: '/my-wallet',
        name: 'MyWallet',
        component: MyWallet,
    },
    {
        path: '/my-wallet/:walletType/create',
        name: 'CreateWallet',
        component: CreateWallet,
        meta: { layout: HeaderLayout, title: 'Add wallet', isBack: false, isCancel: true },
        props: true,
    },
    {
        path: '/my-wallet/edit/:walletId',
        name: 'EditWallet',
        component: EditWallet,
        meta: { layout: HeaderLayout, title: 'Edit wallet', isBack: false, isCancel: true },
        props: true,
    },
    {
        path: '/logout',
        name: 'Logout',
    },
    {
        path: '/icon',
        name: 'Icon',
        component: Icon,
        meta: { layout: HeaderLayout, title: 'Icon', isBack: true, isCancel: false },
        props: true,
    },
    {
        path: '/select-wallet',
        name: 'SelectWallet',
        component: SelectWallet,
        meta: { layout: HeaderLayout, title: 'Select wallet', isBack: false, isCancel: true },
        props: true,
    },
    {
        path: '/add-category',
        name: 'AddCategory',
        component: AddCategory,
        meta: { layout: HeaderLayout, title: 'New Category', isBack: true},
        
    }
]

const router = createRouter({
    history: createWebHistory(),
    routes
});

router.beforeEach(async (to, from, next) => {
    if (to.name === 'CreateWallet') {
        const walletTypeId = to.params.walletTypeId;

        try {
            const response = await axios.get(route('CreateWallet', { walletTypeId }));
            const isFirstTime = response.data.isFirstTime;

            if (isFirstTime) {
                to.meta.layout = NoneLayout;
                delete to.meta.title;
                delete to.meta.isBack;
                delete to.meta.isCancel;
            }
        } catch (error) {
            console.error('Error checking wallet:', error);
        }
    }

    next();
});

export default router;
