import { createApp } from 'vue';
import { createPinia } from 'pinia'
import App from './Index.vue';
import router from './router';
import './assets/main.css'
import Toast from "vue-toastification";
import VueDatePicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css'

const pinia = createPinia()
const app = createApp(App);
app.use(pinia)
app.use(router);
app.component('VueDatePicker', VueDatePicker);
app.use(Toast, {
    transition: "Vue-Toastification__bounce",
    maxToasts: 2,
    newestOnTop: true
});
app.mount('#app');


