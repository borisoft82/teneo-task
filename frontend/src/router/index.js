import { createRouter, createWebHistory } from 'vue-router'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'home',
      component: () => import("../pages/Calendar.vue"),
    },
    {
      path: '/calendar',
      name: 'calendar',
      component: () => import("../pages/Calendar.vue"),
    },
    {
      path: '/arhiva',
      name: 'arhiva',
      component: () => import('../pages/Archive.vue'),
    },
  ],
})

export default router
