import { createRouter, createWebHistory } from 'vue-router'
import login from "./views/Login.vue"
import start from "./views/Start.vue"
import register from "@/views/Register.vue";
import home from "@/views/Home.vue";
import expense from "@/views/Expense.vue";
import addAccountView from "@/views/AddAccountView.vue";
import accountsView from "@/views/AccountsView.vue";
import IncomeViews from "@/views/IncomeViews.vue";
import history from "@/views/History.vue";
import sheetView from "@/views/SheetView.vue";

const routes = [
    {
        path: '/login',
        name: 'login',
        component: login,
        meta: { hideNavbar: true },
    },
    {
        path: '/history',
        name: 'history',
        component: history,
    },
    {
        path: '/add-account',
        name: 'addAccount',
        component: addAccountView,
    },
    {
        path: '/accounts',
        name: 'accounts',
        component: accountsView,
    },
    {
        path: '/income',
        name: 'income',
        component: IncomeViews,
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
    {
        path: '/sheets',
        name: 'sheets',
        component: sheetView
    }
]
const router = createRouter({
    history: createWebHistory(process.env.BASE_URL),
    routes
})
export default router