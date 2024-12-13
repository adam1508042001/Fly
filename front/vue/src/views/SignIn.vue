<template>
  <div class="flex flex-col min-h-screen">
    <!-- Navbar -->
    <NavBar />

    <div class="flex items-center justify-center flex-grow bg-cover bg-center">
      <div class="bg-black bg-opacity-65  p-8 rounded-[40px] shadow-lg w-[500px] h-[400px]">

        <!-- Titre -->
        <div class="text-start mb-6">
          <h1 class="text-[#0004ff] text-4xl font-bold font-['Inter']">FLY</h1>
          <h2 class="text-black text-3xl font-bold">Login</h2>
        </div>

        <!-- Formulaire -->
        <form>
          <!-- Email -->
          <div class="mb-4">
            <label for="email" class="block  font-bold text-white">Email</label>
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
import NavBar  from './NavBar.vue';
</script>

<style>
html, body {
  height: 100%;
  margin: 0;
  font-family: 'Inter', sans-serif;
}
</style>
