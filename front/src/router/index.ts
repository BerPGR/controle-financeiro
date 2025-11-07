import { createRouter, createWebHistory } from 'vue-router'
import Home from '../pages/Home.vue'
import Entries from '../pages/Entries.vue'

const router = createRouter({
    routes: [
        {
            path: '/',
            component: Home
        },
        {
            path: '/entries/:id',
            component: Entries
        }
    ],
    history: createWebHistory(import.meta.env.BASE_URL)
})

export default router