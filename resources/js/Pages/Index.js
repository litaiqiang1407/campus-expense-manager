import { defineAsyncComponent } from 'vue';
export const Home = () => import('./Home/Index.vue');
export const NotFound = () => import('./NotFound/Index.vue');
export const Welcome = () => import('./Welcome/Index.vue');
export const Signup = () => import('./Signup/Index.vue');
export const AppInfo = () => import('./AppInfo/Index.vue');
export const Signin = () => import('./Signin/Index.vue');
export const Account = () => import('./Account/Index.vue');

export const Transaction = () => import('./Transaction/Index.vue');
export const CreateTransaction = () => import('./Transaction/Create.vue');
export const WriteNote = () => import('./Transaction/WriteNote/Index.vue');

export const MyAccount = () => import('./MyAccount/Index.vue');

export const Budget = () => import('./Budget/Index.vue');
export const CreateBudget = () => import('./Budget/Create.vue');

export const Categories = () => import('./Categories/Index.vue');
export const SelectCategory = () => import('./Categories/SelectCategory/Index.vue');

export const Notification = () => import('./Notification/Index.vue');

export const MyWallet = () => import('./MyWallet/Index.vue');
export const CreateWallet = () => import('./MyWallet/Create.vue');
export const EditWallet = () => import('./MyWallet/Edit.vue');
export const SelectWallet = () => import('./MyWallet/SelectWallet/Index.vue');
export const Icon = () => import('./Icon/Index.vue');

export const App = defineAsyncComponent(() => import('./App.vue'));
