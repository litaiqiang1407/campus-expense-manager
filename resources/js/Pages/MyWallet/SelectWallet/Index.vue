<template>
    <div class="py-4 px-2 bg-white h-[52px]">
      <Search class="h-[30px]" :initialQuery="initialQuery" @search="performSearch" />
    </div>
    <div class="max-w-full mx-auto mt-4">
      <div v-for="wallet in wallets" :key="wallet.id">
        <div v-if="wallet.name == 'Total'" class="bg-white rounded-lg flex items-center p-4 shadow-sm w-full h-full mb-2  ">
          <div class="w-full flex items-center justify-between" @click="selectWallet(wallet.id)">
            <div class="flex flex-1 items-center">
              <img
                :alt="`${wallet.name} icon`"
                class="w-10 h-10 rounded-full"
                :src="wallet.icon_path"
              />
              <div class="ml-4 flex-1 flex flex-col justify-between">
                <div class="font-medium">
                  {{ wallet.name }}
                </div>
                <div class="text-secondaryText text-sm">
                  {{ formatBalance(wallet.balance) }}
                </div>
              </div>
              <font-awesome-icon v-if="wallet.id == walletIdSelected || walletIdSelected == undefined" icon="check" class="text-primary" />
            </div>
          </div>
        </div>
      </div>
      <div class="text-sm  mb-2 flex items-center justify-between h-12 px-4">
        <span class="text-secondaryText font-bold">Included in Total</span>
        <span @click="goToMyWallet" class="text-primary font-semibold">View Details</span>
      </div>
      <div v-if="isLoading" class="flex w-screen items-center justify-center h-64">
        <Loading class="size-16"/>
      </div>
      <div v-if="!isLoading && wallets.length > 0">
        <div v-for="wallet in wallets" :key="wallet.id" >
          <div v-if="wallet.name != 'Total'" class="bg-white rounded-lg flex items-center p-4 shadow-sm w-full h-full mb-2  ">
            <div class="w-full flex items-center justify-between" @click="selectWallet(wallet.id)">
              <div class="flex flex-1 items-center">
                <img
                  :alt="`${wallet.name} icon`"
                  class="w-10 h-10 rounded-full"
                  :src="wallet.icon_path"
                />
                <div class="ml-4 flex-1 flex flex-col justify-between">
                  <div class="font-medium">
                    {{ wallet.name }}
                  </div>
                  <div class="text-secondaryText text-sm">
                    {{ formatBalance(wallet.balance) }}
                  </div>
                </div>
                <font-awesome-icon v-if="wallet.id == walletIdSelected" icon="check" class="text-primary" />
              </div>
            </div>
          </div>
        </div>
      </div>
      <div v-else-if="!isLoading && wallets.length == 0">
        <div class="flex w-screen items-center justify-center h-64">
          <h2 class="text-[16px] text-secondaryText">No wallets found</h2>
        </div>
      </div>

      <div class="fixed right-4 bottom-4 size-12 text-[20px]" @click="displayWalletTypes">
        <Add :icon="'plus'" />
      </div>

      <div v-if="openWalletTypes" class="fixed inset-0 bg-black bg-opacity-50 flex items-end justify-center z-50" @click="closeWalletTypes">
        <div class="bg-white w-full rounded-t-lg p-4 z-100" @click.stop>
            <div class="w-full p-2 flex items-center justify-between">
                <h2 class="text-[16px] font-bold">Add Wallet</h2>
                <div class="button-animate size-7 flex items-center justify-center rounded-full border-[1px] border-primary" @click="closeWalletTypes"><font-awesome-icon icon="xmark" class="text-[16px]" /></div>
            </div>
            <div class="px-2 py-4 grid grid-cols-2 gap-4">
                <button
                    v-for="walletType in walletTypes"
                    :key="walletType.id"
                    class="button-animate border-[2px] border-primary p-4 rounded-lg flex items-center justify-center"
                    @click.stop="createWallet(walletType.id)">
                        <h3 class="font-semibold text-[16px]">{{ walletType.name }}</h3>
                </button>
            </div>
        </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import { Add } from '@/Components/Button/Index';
import Loading from '@/Components/Loading/Index.vue';
import { Search } from '@/Components/Header/Components/Index';

const router = useRouter();
const walletIdSelected = router.currentRoute.value.query.walletId;

const isLoading = ref(false);
const wallets = ref([]);
const walletTypes = ref([]);
const openWalletTypes = ref(false);
const totalWalletBalance = ref(0);

const initialQuery = ref('');

const fetchWallets = async () => {
  try {
    isLoading.value = true;
    const response = await axios.get(route('MyWallet'));
    walletTypes.value = response.data.walletTypes;
    wallets.value = response.data.wallets;
    totalWalletBalance.value = response.data.totalWalletBalance;
  } catch (error) {
    console.error('Error fetching wallets:', error);
  } finally {
    isLoading.value = false;
  }
};

const performSearch = async (query) => {
  try {
    isLoading.value = true;
    const response = await axios.get(route('SearchWallets', { search : query}));
    wallets.value = response.data.wallets;
  } catch (error) {
    console.error('Error fetching search results:', error);
  } finally {
    isLoading.value = false;
  }
};

const formatBalance = (balance) => {
  return balance === 0
    ? '$0'
    : `${balance < 0 ? '-$' : '$'}${Number.isInteger(Math.abs(balance)) ? Math.abs(balance) : Math.abs(balance).toFixed(2)}`;
}

const selectWallet = (walletId) => {
  router.push({ name: 'EditTransaction', query: { walletId } });
};

const displayWalletTypes = () => {
  openWalletTypes.value = true;
};

const closeWalletTypes = () => {
  openWalletTypes.value = false;
};

const createWallet = (walletTypeId) => {
  router.push({ name: 'CreateWallet', params: { walletTypeId } });
};

const goToMyWallet = () => {
  router.push({ name: 'MyWallet' });
};

onMounted(fetchWallets);
</script>
