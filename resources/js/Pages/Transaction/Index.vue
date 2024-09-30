<template>
  <div class="flex flex-col h-screen">
    <!-- Header Top -->
    <header class="h-20 px-4 py-2 flex items-center relative">
      <div class="balance-section flex flex-col items-center justify-center flex-1 absolute left-1/2 transform -translate-x-1/2">
        <h2 class="balance-label text-[#A7A7A7] font-bold text-center text-[12px]">Balance</h2>
        <p class="balance-amount text-black text-[20px] font-bold">{{ balance }}</p>
      </div>
      <div class="icons-section flex space-x-4 ml-auto">
        <span class="search-icon cursor-pointer text-black text-[20px] font-normal">
          <font-awesome-icon icon="magnifying-glass" />
        </span>
        <span class="menu-icon cursor-pointer text-black text-[20px] font-normal flex flex-col justify-center">
          <font-awesome-icon icon="ellipsis-vertical" />
        </span>
      </div>
    </header>

    <!-- Header Bottom -->
    <div class="cursor-pointer transaction-type-container bg-[#F3F3F3] flex justify-center items-center p-2 max-w-max mx-auto">
      <img src="/public/assets/img/wallet.jpg" alt="Wallet" class="h-6 w-6 mr-2 rounded-full" />
      <h3 class="transaction-type text-black text-lg mr-2">Cash</h3>
      <font-awesome-icon icon="chevron-down" />
    </div>

    <!-- History Management -->
    <div class="flex justify-between items-center mt-2 w-full max-w-md mx-auto px-4">
      <span class="text-black cursor-pointer font-medium uppercase relative" @click="selectMonth('last')">
        LAST MONTH
        <span v-if="selectedMonth === 'last'" class="custom-underline"></span>
      </span>
      <span class="text-black cursor-pointer font-medium uppercase relative" @click="selectMonth('this')">
        THIS MONTH
        <span v-if="selectedMonth === 'this'" class="custom-underline"></span>
      </span>
      <span class="text-black cursor-pointer font-medium uppercase relative" @click="selectMonth('future')">
        FUTURE
        <span v-if="selectedMonth === 'future'" class="custom-underline"></span>
      </span>
    </div>

    <main class="bg-[#F3F3F3] flex flex-1 items-center justify-center">
      <div v-if="!hasData">
        <NoData message="Tap + to add one" />
      </div>
      <div v-else>
        <p>Your transaction data will appear here.</p>
      </div>
    </main>

    <!-- Footer -->
    <footer class="flex flex-col items-center">

      <span class="text-[8px]">Version 1</span>
    </footer>
  </div>
</template>

<script setup>
import { useRouter } from 'vue-router';
import NoData from '../../Components/NoData/Index.vue';
import { ref } from 'vue';

const hasData = ref(false);
const router = useRouter();
const balance = '$0';
const selectedMonth = ref('this');

const goToSignup = () => {
  router.push({ name: 'Signup' });
};

const selectMonth = (month) => {
  selectedMonth.value = month;
};
</script>

<style scoped>
.custom-underline {
  position: absolute;
  bottom: -4px;
  left: 0;
  right: 0;
  height: 2px;
  background-color: black;
}
</style>
