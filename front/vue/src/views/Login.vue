<template>
  <div class="flex flex-col min-h-screen">
    <!-- Navbar -->
    <NavBar />

    <div class="flex items-center justify-center flex-grow bg-cover bg-center">
      <div class="bg-black bg-opacity-65  p-8 rounded-[40px] shadow-lg w-[600px] ">

        <!-- Titre -->
        <div class="text-start mb-6">
          <h1 class="text-[#ffffff] text-4xl font-bold font-['Inter'] border-white">FLY</h1>
          <h2 class="text-[#02073F] text-3xl font-bold">Login</h2>
        </div>

        <!-- Formulaire -->
        <form @submit.prevent="login">
          <!-- Email -->
          <div class="mb-4">
            <label for="email" class="block font-bold text-white">Email</label>
            <input
              type="email"
              id="email"
              v-model="email"
              placeholder="username@gmail.com"
              class="mt-1 block w-full px-4 py-2 rounded-full border border-gray-300 focus:outline-none focus:ring focus:ring-blue-500 focus:border-blue-500 shadow-sm"
            />
          </div>

          <!-- Mot de passe -->
          <div class="mb-6">
            <label for="password" class="block font-bold text-white">Password</label>
            <input
              type="password"
              id="password"
              v-model="password"
              placeholder="user password"
              class="mt-1 block w-full px-4 py-2 rounded-full border border-gray-300 focus:outline-none focus:ring focus:ring-blue-500 focus:border-blue-500 shadow-sm"
            />
          </div>

          <!-- Affichage des erreurs -->
          <div v-if="errors.length" class="mb-4 px-5 text-red-700 font-bold bg-white/30 bg-opacity-30 backdrop-blur-md rounded-[10px] ">
            <ul>
              <!-- vfor est faite pour itérer sur l'objet errors et afficher des élement en fonctions de ces données  -->
              <!-- item : un élément dans la liste; :key : Une clé unique (souvent un id ou l'index) qui aide Vue à suivre les éléments lors des mises à jour -->
              <li v-for="(error, index) in errors" :key="index">{{ error }}</li>
            </ul>
          </div>


          <!-- Bouton de connexion -->
          <div class="mb-4">
            <button
              type="submit"
              class="w-full bg-[#0004ff] text-white py-2 rounded-full font-bold hover:bg-blue-600 focus:outline-none focus:ring focus:ring-blue-300 transition-all"
            >
              Login
            </button>
          </div>
        </form>

        <!-- Lien pour s'inscrire -->
        <div class="   text-center text-[16px] text-white ">
          <p>Don’t have an account yet?
            <router-link to="/signup" class="text-[#ffffff] font-bold underline">Register for free</router-link>
          </p>
        </div>
      </div>
    </div>
  </div>
  

</template>

<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import axios from 'axios';
import NavBar  from './NavBar.vue';
  
  // Variables réactives pour les champs
  
  const email = ref('');
  const password = ref('');
  const errors = ref([]);


  // initialisation de la visibikiuté de la notification a false 
  const visible = ref(false);
  const router = useRouter();



    // Méthode pour la validation du formulaire
    const login = async ()  => {

      errors.value = []; 


      if (!email.value && !password.value) {
    errors.value.push('Email and password must not be empty');
  } else {
    // Vérifier si l'email est vide
    if (!email.value) {
      errors.value.push('Email must not be empty');
    }

    // Vérifier si le mot de passe est vide
    if (!password.value) {
      errors.value.push('Please enter a password');
    }
  }

  // Si il y a des erreurs, on arrête l'exécution
  if (errors.value.length > 0) {
    return;
  }

  try {
    const response = await axios.post('http://127.0.0.1:8000/api/auth/login', {
      email: email.value,
      password: password.value
    });

    console.log('Login successful:', response.data);

    localStorage.setItem('token', response.data.access_token);
    localStorage.setItem('email', email.value);

    router.push('/search');

    // Réinitialiser les champs après soumission du formulaire
    email.value = '';
    password.value = '';qqqqqq
  } catch (error) {
    console.error('Login failed:', error.response.data);
    errors.value.push('Login failed: ' + error.response.data.message);
  }
};
</script>

<style>
html, body {
  height: 100%;
  margin: 0;
  font-family: 'Inter', sans-serif;
}
</style>
