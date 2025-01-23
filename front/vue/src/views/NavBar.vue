<template>
  <div class="NavBar bg-black bg-opacity-25 my-4 mx-[30px] py-3 px-6 rounded-[60px] flex items-center justify-between">
    <!-- Logo -->
    <div class="flex items-center gap-4">
      <img 
        class="logo w-12 h-12 rounded-full object-cover" 
        src="../assets/logo.jfif" 
        alt="Logo"
      />
      <div class="brand text-[#ffffff] text-2xl font-bold font-['Inter']">
        FLY
      </div>
    </div>

    <!-- Right section: Login & Sign In buttons -->
    <div class="flex items-center justify-end">
      <!-- Center button -->
      <div class="text-black font-bold">
        <button class="px-3 hover:text-blue-500 transition-all">
          About Us
        </button>
        <!-- Login Button -->
        <router-link to="/login">
          <button v-if="!isAuthenticated" 
            class="login h-12 px-6 py-2 mx-4 rounded-full bg-[#0004ff] border border-[#000000] text-[#ffffff] font-bold transition-all"
          >
            Login
          </button>
          <button v-else @click="logout"
            class="login h-12 px-6 py-2 mx-4 rounded-full bg-[#0004ff] border border-[#000000] text-[#ffffff] font-bold transition-all"
          >
            Logout
          </button>
        </router-link>
        <!-- Sign In Button -->
        <router-link to="/signup">
          <button 
            class="signin bg-[#ffffff] bg-opacity-60 h-12 px-6 py-2 rounded-full border border-[#09147a] text-[#09147a] font-bold hover:bg-[#09147a] hover:text-white transition-all"
          >
            Sign In
          </button>
        </router-link>

        <router-link to="/flights">
          <button v-if="isAuthenticated"
            class="signin bg-[#ffffff] bg-opacity-60 h-12 px-6 py-2 rounded-full border border-[#09147a] text-[#09147a] font-bold hover:bg-[#09147a] hover:text-white transition-all"
          >
            Your Flights
          </button>
        </router-link>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import axios from 'axios';

const isAuthenticated = ref(false);
const router = useRouter();

const logout = () => {
  isAuthenticated.value = false;
  
  localStorage.removeItem('token');

  axios.post('http://127.0.0.1:8000/api/auth/logout')
    .then(response => {
      console.log('Logged out successfully');
    })
    .catch(error => {
      console.error('Error logging out:', error);
    });
    
  router.push('/login');
};
</script>

<style scoped>
/* Ajoute des styles personnalisés ici si nécessaire */
</style>
