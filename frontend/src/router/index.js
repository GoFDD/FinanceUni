import { createRouter, createWebHistory } from 'vue-router'
import authService from '@/services/authService'

import Login from '@/views/Login.vue'
import Register from '@/views/Register.vue'
import Dashboard from '@/views/Dashboard.vue'

const routes = [
  { path: '/login', name: 'Login', component: Login },
  { path: '/register', name: 'Register', component: Register },
  {
    path: '/dashboard',
    name: 'Dashboard',
    component: Dashboard,
    meta: { requiresAuth: true, roles: ['student', 'cliente', 'university'] },
  },
]

const router = createRouter({
  history: createWebHistory(),
  routes,
})

router.beforeEach(async (to, from, next) => {
  if (to.meta.requiresAuth) {
    try {
      const user = await authService.getUser()

      if (!user) {
        return next('/login')
      }

      // Checa roles permitidas
      if (to.meta.roles && !to.meta.roles.includes(user.role)) {
        return next('/login')
      }

      next()
    } catch (error) {
      return next('/login')
    }
  } else {
    next()
  }
})

export default router
