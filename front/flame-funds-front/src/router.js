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
import {createToast, withProps} from "mosha-vue-toastify";
import RefreshTokenDialog from "@/components/RefreshTokenDialog.vue";

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
        beforeEnter: authMiddleware,
    },
    {
        path: '/add-account',
        name: 'addAccount',
        component: addAccountView,
        beforeEnter: authMiddleware,
    },
    {
        path: '/accounts',
        name: 'accounts',
        component: accountsView,
        beforeEnter: authMiddleware,
    },
    {
        path: '/income',
        name: 'income',
        component: IncomeViews,
        beforeEnter: authMiddleware,
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
        beforeEnter: authMiddleware,
    },
    {
        path: '/expense',
        name: 'expense',
        component: expense,
        beforeEnter: authMiddleware,
    },
    {
        path: '/sheets',
        name: 'sheets',
        component: sheetView,
        beforeEnter: authMiddleware,
    }
]
const router = createRouter({
    history: createWebHistory(process.env.BASE_URL),
    routes
})

function parseJwt (token) {
    let base64Url = token.split('.')[1];
    let base64 = base64Url.replace(/-/g, '+').replace(/_/g, '/');
    let jsonPayload = decodeURIComponent(atob(base64));

    return JSON.parse(jsonPayload);
}

function authMiddleware (to, from, next) {
    if (sessionStorage.token) {
        const jwtPayload = parseJwt(sessionStorage.token);
        console.log(jwtPayload.exp)
        console.log(Date.now()/1000);
        if (jwtPayload.exp < Date.now()/1000) {
            createToast(withProps(RefreshTokenDialog, { next: next }),
                {
                    position: 'top-center',
                    showIcon: 'true',
                    type: 'default',
                    transition: 'bounce',
                    timeout: -1,
                    showCloseButton: true,
                    swipeClose: true,
                })
        } else {
            next();
        }
    } else {
        next("/login");
    }
}

export default router