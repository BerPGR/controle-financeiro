import { createRouter, createWebHistory } from 'vue-router'
import Home from '../pages/Home.vue'

const router = createRouter({
    routes: [
        {
            path: '/',
            component: Home
        }
    ],
    history: createWebHistory(import.meta.env.BASE_URL)
})

export default router