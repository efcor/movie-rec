import './bootstrap';

import App from './components/App.vue';
import { createApp } from 'vue';

import Vue3TouchEvents from "vue3-touch-events";

const app = createApp(App);
app.use(Vue3TouchEvents);
app.mount('#app');
