import { defineAsyncComponent } from 'vue';

export { default as Home } from './Home/Index.vue';
export { default as NotFound } from './NotFound/Index.vue';
export { default as Welcome } from './Welcome/Index.vue';
export { default as Signup } from './Signup/Index.vue';
export { default as AppInfo } from './AppInfo/Index.vue';
export { default as Signin } from './Signin/Index.vue';
export { default as Account } from './Account/Index.vue';

export const Transaction = () => import('./Transaction/Index.vue');
export const TransactionDetails = () => import('./Transaction/TransactionDetails/Index.vue');
export const CreateTransaction = () => import('./Transaction/Create.vue');
export const EditTransaction = () => import('./Transaction/Edit.vue');
export const WriteNote = () => import('./Transaction/WriteNote/Index.vue');

export const MyAccount = () => import('./MyAccount/Index.vue');
export const Budget = () => import('./Budget/Index.vue');
export const CreateBudget = () => import('./Budget/Create.vue');
export const Categories = () => import('./Categories/Index.vue');
 feat/recurringtransaction

 feat/recurringtransaction
export { default as MyWallet } from './MyWallet/Index.vue';
export { default as CreateWallet } from './MyWallet/Create.vue';
export { default as EditWallet } from './MyWallet/Edit.vue';
export { default as SelectWallet } from './MyWallet/SelectWallet/Index.vue';
export { default as Icon } from './Icon/Index.vue';

export { default as RecurringTransaction } from './Recurring/Index.vue';


export const SelectCategories = () => import('./Categories/SelectCategories/Index.vue');
 master
export const Notification = () => import('./Notification/Index.vue');
export const MyWallet = () => import('./MyWallet/Index.vue');
export const CreateWallet = () => import('./MyWallet/Create.vue');
export const EditWallet = () => import('./MyWallet/Edit.vue');
export const SelectWallet = () => import('./MyWallet/SelectWallet/Index.vue');
export const Icon = () => import('./Icon/Index.vue');

export const App = defineAsyncComponent(() => import('./App.vue'));
 master
