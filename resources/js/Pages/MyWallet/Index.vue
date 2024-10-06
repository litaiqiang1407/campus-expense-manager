<template>
  <div class="max-w-full mx-auto mt-2">
      <div class="text-gray-500 text-sm text-center mb-2 flex items-center h-12 pl-4 font-bold">
          Included in Total
      </div>
      <div v-for="wallet in wallets" :key="wallet.id" class="bg-white rounded-lg flex items-center p-4 shadow-sm w-full h-full mb-2">
          <img
              :alt="`${wallet.name} icon`"
              class="w-10 h-10 rounded-full"
              :src="wallet.icon_path"
          />
          <div class="ml-4 flex-1 flex flex-col justify-between">
              <div class="text-gray-800 font-medium">
                  {{ wallet.name }}
              </div>
              <div class="text-gray-500 text-sm">
                  ${{ wallet.balance.toFixed(2) }}
              </div>
          </div>
          <font-awesome-icon icon="ellipsis-vertical" class="text-[24px]" />
      </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';

const wallets = ref([]);

const fetchWallets = async () => {
  try {
      const response = await axios.get(route('MyWallet')); 
      wallets.value = response.data;
      console.log('Wallets:', wallets.value);
  } catch (error) {
      console.error('Error fetching wallets:', error);
  }
};

onMounted(() => {
  fetchWallets();
});
</script>
