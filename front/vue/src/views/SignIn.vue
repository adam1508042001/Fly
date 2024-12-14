<template>
  <div class="flex flex-col min-h-screen">
    <!-- Navbar -->
    <NavBar />

    <div class="flex items-center justify-center flex-grow bg-cover bg-center">
      <div class="bg-black bg-opacity-65  p-8 rounded-[40px] shadow-lg w-[600px] ">

        <!-- Titre -->
        <div class="text-start mb-6">
          <h1 class="text-[#ffffff] text-4xl font-bold font-['Inter'] border-white">FLY</h1>
          <h2 class="text-black text-3xl font-bold">Login</h2>
        </div>

        <!-- Formulaire -->
         <!-- @submit.prevent pour ne pas   recharger  la page pour pouvoir gérer le formualire  -->
        <form  @submit.prevent="validation" >
          <!-- Email -->
          <div  class="mb-4">
            <label for="email" class="block  font-bold text-white">Email</label>
            <!-- j'ajoute des variablmes reactives pour stocker les valeurs  avec id -->
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
          <div v-if="errors.length" class="mb-4 text-red-500  ">
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
          <a href="/register" class="text-[#ffffff] font-bold underline">Register for free</a></p>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import NavBar  from '../views/NavBar.vue';

  
  // Variables réactives pour les champs
  
  const email = ref('');
  const password = ref('');
  const errors = ref([]);





    // Méthode pour la validation du formulaire
    const validation = (e)  => {

      errors.value = []; 


      if (email.value && password.value) {
        alert ('formulaire validé ! '); 
        return true;
      }
      


      if (!email.value) {
        errors.value.push('Email must not be empty');
      }
      if (!password.value) {
        errors.value.push('please enter a password');
      }

    
    }
  

</script>

<style>
html, body {
  height: 100%;
  margin: 0;
  font-family: 'Inter', sans-serif;
}
</style>
