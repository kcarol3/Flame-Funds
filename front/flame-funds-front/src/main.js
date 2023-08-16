import 'bootstrap/dist/css/bootstrap.css'
import { createApp } from 'vue'
import App from './App.vue'
import router from './router'
import 'bootstrap/dist/js/bootstrap.js'
import 'bootstrap-icons/font/bootstrap-icons.css'
import VueDatePicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css'
import PrimeVue from 'primevue/config';
import "primevue/resources/themes/lara-light-indigo/theme.css";
import InputNumber from 'primevue/inputnumber';
import Textarea from 'primevue/textarea';
import Calendar from 'primevue/calendar';
import AutoComplete from 'primevue/autocomplete';
import InputText from 'primevue/inputtext';
import Dropdown from 'primevue/dropdown';
import Button from 'primevue/button';

createApp(App)
    .use(router)
    .component('VueDatePicker',VueDatePicker)
    .use(PrimeVue)
    .component("InputNumber", InputNumber)
    .component('TextArea', Textarea)
    .component('Calendar', Calendar)
    .component('AutoComplete', AutoComplete)
    .component("InputText", InputText)
    .component("Dropdown", Dropdown)
    .component("Button", Button)
    .mount('#app');

