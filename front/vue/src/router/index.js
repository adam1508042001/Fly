import { createRouter, createWebHistory } from 'vue-router'
import Login from '../views/Login.vue'
import Signup from '../views/Signup.vue'
import SearchFly from '../views/SearchFly.vue'
import Reservation from '../views/Reservation.vue'

const routes = [
  { path: '/', redirect: '/search' },
  { path: '/login', component: Login },
  { path: '/signup', component: Signup },
  { path: '/search', component: SearchFly },
  { path: '/reservation', component: Reservation }
]

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes,
})

export default router
