<template>
  <div class="flex flex-col min-h-screen">
    <!-- Navbar -->
    <NavBar />

    <div class="flex items-center justify-center flex-grow bg-cover bg-center">
      <div class="bg-black bg-opacity-65 p-6 rounded-[40px] shadow-lg w-[600px]">
        <!-- Titre -->
        <div class="text-start mb-6">
          <h1 class="text-[#ffffff] text-4xl font-bold font-['Inter'] border-white">FLY</h1>
          <h2 class="text-[#02073F] text-3xl font-bold">Sign Up</h2>
        </div>

        <!-- Formulaire -->
        <form @submit.prevent="register">
          <!-- Firstname -->
          <div class="mb-4">
            <label for="first_name" class="block font-bold text-white">First Name</label>
            <input
              type="text"
              id="first_name"
              v-model="first_name"
              placeholder="First name"
              class="mt-1 block w-full px-4 py-2 rounded-full border border-gray-300 focus:outline-none focus:ring focus:ring-blue-500 focus:border-blue-500 shadow-sm"
            />
          </div>

          <!-- Lastname -->
          <div class="mb-4">
            <label for="last_name" class="block font-bold text-white">Last Name</label>
            <input
              type="text"
              id="last_name"
              v-model="last_name"
              placeholder="Last name"
              class="mt-1 block w-full px-4 py-1 rounded-full border border-gray-300 focus:outline-none focus:ring focus:ring-blue-500 focus:border-blue-500 shadow-sm"
            />
          </div>

          <!-- Date Of Birth -->
          <div class="mb-4">
            <label for="date_of_birth" class="block font-bold text-white">Date Of Birth</label>
            <input
              type="date"
              id="date_of_birth"
              min="1800-01-01"
              :max="maxDob"
              v-model="date_of_birth"
              class="mt-1 block w-full px-4 py-2 rounded-full border border-gray-300 focus:outline-none focus:ring focus:ring-blue-500 focus:border-blue-500 shadow-sm"
            />
          </div>

          <!-- Email -->
          <div class="mb-4">
            <label for="email" class="block font-bold text-white">Email</label>
            <input
              type="email"
              id="email"
              v-model="email"
              placeholder="example@gmail.com"
              class="mt-1 block w-full px-4 py-2 rounded-full border border-gray-300 focus:outline-none focus:ring focus:ring-blue-500 focus:border-blue-500 shadow-sm"
            />
          </div>

          <!-- Password -->
          <div class="mb-4">
            <label for="password" class="block font-bold text-white">Password</label>
            <input
              type="password"
              id="password"
              v-model="password"
              placeholder="Password"
              class="mt-1 block w-full px-4 py-2 rounded-full border border-gray-300 focus:outline-none focus:ring focus:ring-blue-500 focus:border-blue-500 shadow-sm"
            />
          </div>

          <!-- Erreurs -->
          <div v-if="errors.length" class="mb-4 px-5 text-red-700 font-bold bg-white/30 backdrop-blur-md rounded-[10px]">
            <ul>
              <li v-for="(error, index) in errors" :key="index">{{ error }}</li>
            </ul>
          </div>

          <!-- Bouton de connexion -->
          <div class="mb-4">
            <button
              type="submit"
              class="w-full bg-[#0004ff] text-white py-2 rounded-full font-bold hover:bg-blue-600 focus:outline-none focus:ring focus:ring-blue-300 transition-all"
            >
              Sign Up
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- Notification -->
    <div v-show="visible" class="flex justify-center m-[30px]">
      <div class="h-[60px] p-[20px] border-4 border-green-600 bg-black opacity-90 rounded-[30px] flex items-center justify-center">
        <p class="text-[#228B22] text-[25px] font-bold">Bien enregistré</p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import NavBar from './NavBar.vue';
import axios from 'axios';

// Variables réactives pour les champs
const first_name = ref('');
const last_name = ref('');
const date_of_birth = ref('');
const email = ref('');
const password = ref('');
const errors = ref([]);
const visible = ref(false);
const router = useRouter();

// Valeur dynamique pour la date maximale
const maxDob = ref('');

onMounted(() => {
  const today = new Date();
  const year = today.getFullYear() - 18; 
  const month = String(today.getMonth() + 1).padStart(2, '0');
  const day = String(today.getDate()).padStart(2, '0');
  maxDob.value = `${year}-${month}-${day}`;
});

// Méthode pour la validation du formulaire
const validation = async () => {
  errors.value = [];

  if (!email.value && !password.value && !first_name.value && !last_name.value && !date_of_birth.value) {
    errors.value.push('You have to put all informations please!');
  } else {
    if (!email.value) {
      errors.value.push('Email must not be empty');
    }
    if (!password.value) {
      errors.value.push('Please enter a password');
    }
    if (!first_name.value) {
      errors.value.push('First name must not be empty');
    }
    if (!last_name.value) {
      errors.value.push('Last name must not be empty');
    }
    if (!date_of_birth.value) {
      errors.value.push('Date of birth must not be empty');
    }

    if (errors.value.length > 0) return;

    console.log('Données envoyées :', {
      first_name: first_name.value,
      last_name: last_name.value,
      date_of_birth: date_of_birth.value,
      email: email.value,
      password: password.value,
    });

    visible.value = true;

    setTimeout(() => {
      visible.value = false;
    }, 3000);

    // Réinitialiser les champs après soumission
    first_name.value = '';
    last_name.value = '';
    date_of_birth.value = '';
    email.value = '';
    password.value = '';
  }
};

// Utilisateurs
async function register() {
  try {
    const response = await axios.post('http://localhost:8000/api/auth/register', {
      first_name: first_name.value,
      last_name: last_name.value,
      date_of_birth: date_of_birth.value,
      email: email.value,
      password: password.value,
    });

    router.push('/login');

    return response.data;
  } catch (error) {
    console.error(error);
    errors.value.push('An error occurred while registering. Please try again.');
  }
}
</script>

<style>
html,
body {
  height: 100%;
  margin: 0;
  font-family: 'Inter', sans-serif;
}
</style>