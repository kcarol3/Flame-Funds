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
import VueApexCharts from "vue3-apexcharts";
import Panel from 'primevue/panel';
import Divider from 'primevue/divider';
import Card from 'primevue/card';
import RadioButton from 'primevue/radiobutton';

createApp(App)
    .use(router)
    .use(PrimeVue)
    .use(VueApexCharts)
    .component('Divider', Divider)
    .component('Card', Card)
    .component('Panel', Panel)
    .component("RadioButton", RadioButton)
    .component('VueDatePicker',VueDatePicker)
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

