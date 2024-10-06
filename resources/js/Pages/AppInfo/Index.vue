<template>
  <div class="w-full flex flex-col items-center justify-start pt-5" style="background-color: #EFFBFF;">
    <div class="flex flex-col items-center p-4">
      <img src="/public/assets/img/logo.png" alt="Logo" class="h-24 w-24 rounded-xl" />
      <h3 class="text-black font-semibold text-2xl mt-2">Money Lover</h3>
      <h1 class="text-gray-400 font-semibold text-sm">Version 8.23.0.3</h1>
      <h1 class="text-gray-400 font-semibold text-sm mb-2">by Finsify</h1>
    </div>
 

  <div class="w-full bg-white flex flex-col items-center">
    <div class="flex justify-center w-full px-6 pt-4">
      <div class="flex flex-col items-center flex-1">
        <img src="/public/assets/img/logobook.png" alt="Walkthrough Icon" class="h-12 w-12" />
        <div>Walkthrough</div>
      </div>
      <div class="h-15 w-0.5 bg-gray-300 mx-2"></div>
      <div class="flex flex-col items-center flex-1">
        <img src="/public/assets/img/logoquestion.png" alt="Support Icon" class="h-12 w-12" />
        <div>Support</div>
      </div>
    </div>

    <div class="mt-6 text-left px-6">
      <div class="flex items-center mb-4">
        <img src="/public/assets/img/logofacebook.png" alt="Support Icon" class="w-8 h-8 mr-2" />
        <span>Like our Facebook page</span>
      </div>
      <div class="flex items-center mb-4">
        <img src="/public/assets/img/logogroup.png" alt="Support Icon" class="w-8 h-8 mr-2" />
        <span>Join our beta testing group</span>
      </div>
      <div class="flex items-center mb-4">
        <img src="/public/assets/img/logotranslate.png" alt="Support Icon" class="w-8 h-8 mr-2" />
        <span>Help Us Translate</span>
      </div>
      <div class="flex items-center mb-4">
        <img src="/public/assets/img/logobusinesspartnership.png" alt="Support Icon" class="w-8 h-8 mr-2" />
        <span>For business partnership</span>
      </div>
    </div>
  </div>

  <div class="w-full text-left py-4 bg-white mt-2">
    <p class="text-gray-400 text-xs pl-2">© 2024 Finsify. All rights reserved.</p>
  </div>


  <button @click="goBack" class="fixed top-2 left-2 text-black text-2xl bg-transparent border-none cursor-pointer">
    <i class="fa-solid fa-arrow-left"></i>
  </button>
  </div>


</template>
 
<script setup>
const goBack = () => {
  window.history.back();
};
</script>
<style>
  body {
    background-color: #effbff;
  }
</style>