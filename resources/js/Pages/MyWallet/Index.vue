<template>
  <header class="sticky h-13 flex items-center justify-between px-4 py-2 bg-white shadow z-50 gap-4">
    <div class="flex flex-1 items-center gap-4 h-[30px]">
      <font-awesome-icon icon="arrow-left" class="text-[20px]" @click="goBack" />
      <h1 v-if="!isSearching" class="text-[20px] font-semibold h-full">My Wallets</h1>
      <Search v-if="isSearching" :initialQuery="initialQuery" historyKey="walletSearchHistory" @search="performSearch" />
    </div>
    <div class="flex items-center space-x-4">
      <font-awesome-icon icon="magnifying-glass" class="text-[20px] cursor-pointer" @click.stop="toggleSearch" />
    </div>
  </header>
    <div class="max-w-full mx-auto">
      <div class="text-[14px] text-secondaryText p-4 text-center flex items-center font-bold">
        Included in Total
      </div>
      <div v-if="isLoading" class="flex w-screen items-center justify-center h-64">
        <Loading class="size-16"/>
      </div>
      <div v-if="!isLoading && wallets.length > 0">
        <div v-for="wallet in wallets" :key="wallet.id" >
          <div v-if="wallet.name != 'Total'" class="bg-white rounded-lg flex items-center p-4 shadow-sm w-full h-full">
            <div class="w-full flex items-center justify-between" @click="editWallet(wallet.id)">
              <div class="flex flex-1 items-center">
                <img
                  :alt="`${wallet.name} icon`"
                  class="size-10 rounded-full"
                  :src="wallet.icon_path"
                />
                <div class="ml-4 flex-1 flex flex-col justify-between">
                  <h3 class="font-medium text-[16px]">
                    {{ wallet.name }}
                  </h3>
                  <div class="text-secondaryText text-[14px]">
                    {{ formatBalance(wallet.balance) }}
                  </div>
                </div>
              </div>
              <button class="w-[32px] h-full" @click.stop="confirmDelete(wallet.id)">
                <font-awesome-icon icon="xmark" class="text-[16px] text-redText" />
              </button>
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
import { ref, onMounted, watch } from 'vue';
import { useRouter } from 'vue-router';
import { Add } from '@/Components/Button/Index';
import Loading from '@/Components/Loading/Index.vue';
import Swal from 'sweetalert2';
import { Search } from '@/Components/Header/Components/Index';

const router = useRouter();

const isLoading = ref(false);
const wallets = ref([]);
const walletTypes = ref([]);
const openWalletTypes = ref(false);
const totalWalletBalance = ref(0);

const isSearching = ref(false);
const initialQuery = ref('');

const searchHistory = ref([]);
const STORAGE_KEY = 'walletSearchHistory';

const saveSearchHistory = (query) => {
  if (!query.trim()) return; 

  searchHistory.value = searchHistory.value.filter(item => item !== query);

  searchHistory.value.unshift(query);
  searchHistory.value = searchHistory.value.slice(0, 5); 

  localStorage.setItem(STORAGE_KEY, JSON.stringify(searchHistory.value));
};

const toggleSearch = () => {
  isSearching.value = !isSearching.value;
  if (isSearching.value) {
    setTimeout(() => document.querySelector('input[type="text"]').focus(), 0);
  }
};

const performSearch = async (query) => {
  try {
    isLoading.value = true;
    const response = await axios.get(route('SearchWallets', { search : query}));
    wallets.value = response.data.wallets;
    saveSearchHistory(query);
  } catch (error) {
    console.error('Error fetching search results:', error);
  } finally {
    isLoading.value = false;
  }
};

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

const formatBalance = (balance) => {
  return balance === 0 
    ? '$0' 
    : `${balance < 0 ? '-$' : '$'}${Number.isInteger(Math.abs(balance)) ? Math.abs(balance) : Math.abs(balance).toFixed(2)}`;
}

const displayWalletTypes = () => {
  openWalletTypes.value = true;
};

const closeWalletTypes = () => {
  openWalletTypes.value = false;
};

const createWallet = (walletTypeId) => {
  router.push({ name: 'CreateWallet', params: { walletTypeId } });
};

const editWallet = (walletId) => {
  router.push({ name: 'EditWallet', params: { walletId } });
};

const confirmDelete = (walletId) => {
  Swal.fire({
    title: 'Are you sure?',
    text: 'You won\'t be able to revert this!',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#00BC2A',
    cancelButtonColor: '#FF2121',
    confirmButtonText: 'Yes, delete it!',
    cancelButtonText: 'No, cancel!',
  }).then((result) => {
    if (result.isConfirmed) {
      deleteWallet(walletId);
    }
  });
};

const deleteWallet = async (walletId) => {
  try {
    await axios.post(route('DeleteWallet', { walletId }));
    wallets.value = wallets.value.filter(wallet => wallet.id !== walletId);

    if (wallets.value.length === 0) {
      window.location.href = route('CreateWallet', { walletTypeId: 1 });
    }
  } catch (error) {
    console.error('Error deleting wallet:', error);
  }
};

const goBack = () => {
    window.history.back()
};

onMounted(fetchWallets);
</script>