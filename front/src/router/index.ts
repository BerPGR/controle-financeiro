import { createRouter, createWebHistory } from 'vue-router'
import Home from '../pages/Home.vue'
import Entries from '../pages/Entries.vue'
import Dashboard from '../pages/Dashboard.vue'
import Settings from '../pages/Settings.vue'

const router = createRouter({
    routes: [
        {
            path: '/',
            component: Home
        },
        {
            path: '/entries/:id',
            component: Entries
        },
                {
            path: '/dashboard',
            component: Dashboard
        },
                {
            path: '/settings',
            component: Settings
        },
    ],
    history: createWebHistory(import.meta.env.BASE_URL)
})

export default router