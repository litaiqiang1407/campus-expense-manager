import '../css/app.css';
import './bootstrap';
import App from "./Pages/App.vue";
import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import FontAwesomeIcon from './Components/FontAwesomeIcon/Index';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';
import router from './Router/Index';
import Toast from 'vue-toastification';
import 'vue-toastification/dist/index.css';

const toastOptions = {
    position: "top-right",
    timeout: 3000,
    closeOnClick: true,
    pauseOnHover: true,
    draggable: true,
};

createInertiaApp({
    resolve: name => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, props, plugin }) {
        return createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(router)
            .component('font-awesome-icon', FontAwesomeIcon)
            .use(ZiggyVue, { ziggy: Ziggy })
            .use(Toast, toastOptions)
            .mount(el);
    },
});
