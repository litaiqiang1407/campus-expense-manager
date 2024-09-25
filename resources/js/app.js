import './bootstrap';

import { createApp } from 'vue';

import app from "./Components/App.vue";

import router from "./Router/Index";

createApp(app).use(router).mount('#app');