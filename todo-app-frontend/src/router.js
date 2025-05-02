import { createMemoryHistory, createRouter } from 'vue-router'

import Dashboard from './components/Dashboard.vue'
import Login from './components/Login.vue'
import Register from './components/Register.vue'

const routes = [
  { path: '/', 
    component: Login, 
    meta: {
      title: 'Home Page'
    }
  },
  { path: '/register',
    component: Register
  },
  { path: '/dashboard',
    component: Dashboard,
    meta: { requiresAuth: true }
  },
]

const router = createRouter({
  history: createMemoryHistory(),
  routes,
})

router.beforeEach((to, from, next) => {
  const token = localStorage.getItem('token')
  const loginPath = '/'
  const registerPath = '/register'
  const dashboardPath = '/dashboard'

  if (to.path === loginPath && token) {
    next(dashboardPath)
  } else if (to.path == registerPath && token) {
    next(dashboardPath)
  } else if (to.path !== registerPath && to.path !== loginPath && !token) {
    next(loginPath)
  } else {
    next()
  }
})

export default router