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
      <div class="fixed right-4 bottom-4 w-16 h-16 text-[24px]" @click="displayWalletTypes">
        <Add :icon="'plus'" />
      </div>
  
      <div v-if="openWalletTypes" class="fixed inset-0 bg-black bg-opacity-50 flex items-end justify-center z-50" @click="closeWalletTypes">
        <div class="bg-white w-full rounded-t-lg p-4 z-100" @click.stop>
            <div class="w-full p-2">
                <h2 class="text-lg font-bold">Add Wallet</h2>
            </div>
            <div class="p-4 grid grid-cols-2 gap-4">
                <button 
                    v-for="walletType in walletTypes" 
                    :key="walletType.id" 
                    class="button-animate bg-gray-100 p-4 rounded-lg flex items-center justify-center"
                    @click.stop>
                        <h3 class="font-bold">{{ walletType.name }}</h3>
                </button>
            </div>
        </div>
    </div>

    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { Add } from '@/Components/Button/Index';

const wallets = ref([]);
const walletTypes = ref([]);
const openWalletTypes = ref(false);

const fetchWallets = async () => {
  try {
    const response = await axios.get(route('MyWallet'));
    walletTypes.value = response.data.walletTypes;
    wallets.value = response.data.wallets;
  } catch (error) {
    console.error('Error fetching wallets:', error);
  }
};

const displayWalletTypes = () => {
  openWalletTypes.value = true;
};

const closeWalletTypes = () => {
  openWalletTypes.value = false;
};

onMounted(() => {
  fetchWallets();
});
</script>