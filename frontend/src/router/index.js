import { createRouter, createWebHistory } from 'vue-router'
import authService from '@/services/authService'

// Views principais
import Login from '@/views/Login.vue'
import Register from '@/views/Register.vue'
import VerifyEmail from '@/views/VerifyEmail.vue'

// Dashboard layout
import Dashboard from '@/views/dashboard/Dashboard.vue'

const routes = [
  // Login e registro
  { path: '/login', name: 'Login', component: Login },
  { path: '/register', name: 'Register', component: Register },
  { path: '/verify-email/:token', name: 'VerifyEmail', component: VerifyEmail },

  // Dashboard
  {
    path: '/dashboard',
    component: Dashboard, // <- layout com sidebar + router-view
    meta: { requiresAuth: true, roles: ['student', 'client', 'university'] },
    children: [
      {
        path: '',
        redirect: '/dashboard/inicio',
      },
      {
        path: 'inicio',
        name: 'DashboardInicio',
        component: () => import('@/views/dashboard/DashboardInicial.vue'),
        meta: { requiresAuth: true },
      },
      {
        path: 'despesas',
        name: 'DashboardDespesas',
        component: () => import('@/views/dashboard/Despesas.vue'),
        meta: { requiresAuth: true },
      },
      {
        path: 'receitas',
        name: 'DashboardReceitas',
        component: () => import('@/views/dashboard/Receitas.vue'),
        meta: { requiresAuth: true },
      },
      {
        path: 'relatorios',
        name: 'DashboardRelatorios',
        component: () => import('@/views/dashboard/Relatorios.vue'),
        meta: { requiresAuth: true },
      },
      {
        path: 'bancos',
        name: 'DashboardBancos',
        component: () => import('@/views/dashboard/Bancos.vue'),
        meta: { requiresAuth: true },
      },
      {
        path: 'perfil',
        name: 'DashboardPerfil',
        component: () => import('@/views/dashboard/Perfil.vue'),
        meta: { requiresAuth: true },
      },
      {
        path: 'planos',
        name: 'DashboardPlanos',
        component: () => import('@/views/dashboard/Planos.vue'),
        meta: { requiresAuth: true },
      },
    ],
  },

  //  se a rota não existe, manda para login
  //{ path: '/:pathMatch(.*)*', redirect: '/login' },
]

const router = createRouter({
  history: createWebHistory(),
  routes,
})

// Middleware de autenticação
router.beforeEach(async (to, from, next) => {
  if (to.meta.requiresAuth) {
    try {
      const user = await authService.getUser()

      if (!user) {
        return next('/login')
      }

      // Checar roles permitidas
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
