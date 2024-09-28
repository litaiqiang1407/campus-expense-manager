import './bootstrap';

import { createApp } from 'vue';

import App from "./Components/App.vue";

import router from "./Router/Index";

import FontAwesomeIcon from './Components/FontAwesomeIcon/Index'

const app = createApp(App);

app.component('font-awesome-icon', FontAwesomeIcon);

app.use(router).mount('#app');