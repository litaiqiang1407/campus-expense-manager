<template>
        <div class="min-h-screen">
            <Header :totalBalance="totalBalance" />
            <main class="p-4 flex flex-col space-y-4">
                <div class="bg-white rounded-[16px] py-4 pl-4 shadow">
                    <div class="flex items-center justify-between border-b-[1px] border-secondaryText pb-2 pr-4">
                        <span class="font-semibold text-[14px]">My Wallets</span>
                        <a href="/my-wallet" class="text-primary font-semibold text-[10px]">See all</a>
                    </div>
                    <div v-if="isLoading" class="w-full h-32 flex items-center justify-center">
                      <Loading class="size-8"/>
                    </div>
                    <div v-for="(wallet, index) in walletList" :index="index" class="flex border-t-[1px] items-center justify-between py-2 pr-4">
                        <div class="flex items-center">
                            <img :src="wallet?.icon_path || '/assets/img/wallet.png'" alt="Wallet" class="w-8 h-8" />
                            <span class="ml-4 font-semibold text-[14px] line-clamp-1">{{ wallet?.name || 'My Wallets' }}</span>
                        </div>
                        <span class="font-medium text-[14px]">{{ formatBalance(wallet?.balance)  }}</span>
                    </div>
                </div>
                <Report />
                <Spending />
                <Transaction />
            </main>
        </div>
</template>

<script setup>
import { Header, Report, Spending, Transaction } from '@/Pages/Home/Components/Index.js';
import { ref, onMounted } from 'vue';
import Loading  from '@/Components/Loading/Index.vue'; 


const totalBalance = ref(0);
const walletList = ref([])
const isLoading = ref(false);

const fetchWallets = async () => {
  try {
    isLoading.value = true;
    const response = await axios.get(route('Home'));
    walletList.value = response.data.walletList
    totalBalance.value = response.data.totalBalance;
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
onMounted(fetchWallets);
</script>