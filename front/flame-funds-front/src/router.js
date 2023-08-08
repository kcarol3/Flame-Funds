import { createRouter, createWebHistory } from 'vue-router'
import login from "./views/Login.vue"
import start from "./views/Start.vue"
import register from "@/views/Register.vue";
const routes = [
    {
        path: '/login',
        name: 'login',
        component: login,
    },
    {
        path: '/start',
        name: 'start',
        component: start,
    },
    {
        path: '/register',
        name: 'register',
        component: register,
    }

]
const router = createRouter({
    history: createWebHistory(process.env.BASE_URL),
    routes
})
export default router