<template>
  <div class="app-container">
    <!-- Background fixe -->
    <div class="fixed-background">
      <img src="./assets/bgimg.webp" alt="Background Image" class="bg-image" />
    </div>

    <!-- Container pour le contenu scrollable -->
    <div class="content-container">
      <router-view />
    </div>
  </div>
</template>

<script setup>
/* Pas de logique spécifique pour l'instant */
</script>

<style scoped>
.app-container {
  position: relative;
  min-height: 100vh;
  width: 100%;
}

.fixed-background {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100vh;
  z-index: -1;
}

.bg-image {
  width: 100%;
  height: 100%;
  object-fit: cover;
  opacity: 0.9;
}

.content-container {
  position: relative;
  width: 100%;
  min-height: 100vh;
  z-index: 1;
}

html, body {
  margin: 0;
  padding: 0;
  height: 100%;
  overflow-x: hidden;
}

#app {
  height: 100%;
}
</style>