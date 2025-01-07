<template>
  <div class="NavBar bg-black bg-opacity-25    my-4 mx-[300px] py-3 px-6   rounded-[60px] flex items-center justify-between  ">
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
    <div class="flex items-center   justify-end ">

      <!-- Center button -->
    <div class=" text-black font-bold">
      <button class=" px-3 hover:text-blue-500 transition-all">
        About Us
      </button>
       <!-- Login Button -->
       <button 
        class="login h-12 px-6 py-2 mx-4 rounded-full  bg-[#0004ff]  border  border-[#000000] text-[#ffffff] font-bold  transition-all"
      >
        Login
      </button>
      <!-- Sign In Button -->
      <button 
        class="signin h-12 px-6 py-2 rounded-full border border-[#09147a] text-[#09147a] font-bold hover:bg-[#09147a] hover:text-white transition-all"
      >
        Sign In
      </button>

    </div>

     

      
    </div>
  </div>
</template>

<script setup>
/* Pas de logique spécifique pour l'instant */
</script>

<style scoped>
/* Ajoute des styles personnalisés ici si nécessaire */
</style>
