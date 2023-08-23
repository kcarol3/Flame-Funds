import { createRouter, createWebHistory } from 'vue-router'
import login from "./views/Login.vue"
import start from "./views/Start.vue"
import register from "@/views/Register.vue";
import home from "@/views/Home.vue";
import expense from "@/views/Expense.vue";
import addAccountView from "@/views/AddAccountView.vue";

const routes = [
    {
        path: '/login',
        name: 'login',
        component: login,
        meta: { hideNavbar: true },
    },
    {
        path: '/add-account',
        name: 'addAccount',
        component: addAccountView,
    },
    {
        path: '/start',
        name: 'start',
        component: start,
        meta: { hideNavbar: true },
    },
    {
        path: '/register',
        name: 'register',
        component: register,
        meta: { hideNavbar: true },
    },
    {
        path: '/home',
        name: 'home',
        component: home,
    },
    {
        path: '/expense',
        name: 'expense',
        component: expense,
    },

]
const router = createRouter({
    history: createWebHistory(process.env.BASE_URL),
    routes
})
export default router