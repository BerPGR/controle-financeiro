import { createRouter, createWebHistory } from "vue-router";
import Home from "../pages/Home.vue";
import Entries from "../pages/Entries.vue";
import Dashboard from "../pages/Dashboard.vue";
import Settings from "../pages/Settings.vue";
import Auth from "../pages/Auth.vue";

const router = createRouter({
  routes: [
    {
      path: "/",
      component: Home,
    },
    {
      path: "/entries/:id",
      component: Entries,
    },
    {
      path: "/dashboard",
      component: Dashboard,
    },
    {
      path: "/settings",
      component: Settings,
    },
    {
      path: "/auth",
      component: Auth,
      meta: { hideLayout: true },
    },
  ],
  history: createWebHistory(import.meta.env.BASE_URL),
});

export default router;
