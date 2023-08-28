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
import ProgressSpinner from 'primevue/progressspinner';
import Menu from 'primevue/menu';
import Sidebar from 'primevue/sidebar';
import Checkbox from 'primevue/checkbox';
import SpeedDial from 'primevue/speeddial';
import ScrollPanel from 'primevue/scrollpanel';
import Dialog from 'primevue/dialog';

createApp(App)
    .use(router)
    .component('VueDatePicker',VueDatePicker)
    .use(PrimeVue)
    .component("Dialog", Dialog)
    .component("ScrollPanel", ScrollPanel)
    .component("SpeedDial", SpeedDial)
    .component("Checkbox", Checkbox)
    .component("Sidebar", Sidebar)
    .component("ProgressSpinner", ProgressSpinner)
    .component("InputNumber", InputNumber)
    .component("Menu", Menu)
    .component('TextArea', Textarea)
    .component('Calendar', Calendar)
    .component('AutoComplete', AutoComplete)
    .component("InputText", InputText)
    .component("Dropdown", Dropdown)
    .component("Button", Button)
    .mount('#app');

