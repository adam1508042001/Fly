<template>
  <div class="w-full h-full opacity-90">
    <!-- Background -->
    <div class="relative w-full h-screen ">
      <!-- Image de fond -->
      <img src="./assets/bgimg.webp" alt="Background Image" class="absolute top-0 left-0 w-full h-full object-cover " />

      <!-- Contenu principal -->
      <div class="relative z-10">


        <!-- les composants -->
        <!-- <Login /> -->
         <signup />

      </div>
    </div>
  </div>
</template>

<script setup>
// import Login from './views/Login.vue';
import Signup from './views/Signup.vue';
</script>

<style scoped>
/* Applique une hauteur correcte */
html,
body,
#app {
  height: 100%;
  margin: 0;
}
</style>
