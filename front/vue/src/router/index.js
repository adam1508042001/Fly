import { createRouter, createWebHistory } from 'vue-router'
import Login from '../views/Login.vue'
import Signup from '../views/Signup.vue'
import SearchFly from '../views/SearchFly.vue'
import Reservation from '../views/Reservation.vue'
import YourFlights from '../views/YourFlights.vue'
import ReturnFlight from '../views/ReturnFlight.vue'

const routes = [
  { path: '/', redirect: '/login' },
  { path: '/login', component: Login },
  { path: '/signup', component: Signup },
  { path: '/search', component: SearchFly },
  { path: '/reservation', component: Reservation },
  { path: '/your-flights', component: YourFlights },
  { path: '/return-flights', component: ReturnFlight }
]

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes,
})

export default router
