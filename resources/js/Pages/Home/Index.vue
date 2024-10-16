<template>
        <div class="min-h-screen">
            <Header :totalBalance="totalBalance" />
            <main class="p-4 flex flex-col space-y-4">
                <div class="bg-white rounded-lg py-2 pl-2 shadow">
                    <div class="flex items-center justify-between border-b-[1px] border-secondaryText pb-2 pr-2">
                        <span class="font-semibold text-[14px]">My Wallets</span>
                        <button class="text-primary font-semibold text-[10px]">See all</button>
                    </div>
                    <div class="flex items-center justify-between py-2 pr-2">
                        <div class="flex items-center">
                            <img :src="wallet?.icon?.path || '/assets/img/wallet.png'" alt="Wallet" class="w-8 h-8" />
                            <span class="ml-2 font-semibold text-[14px]">{{ wallet?.name || 'My Wallets' }}</span>
                        </div>
                        <span class="font-semibold text-[14px]">$ {{ wallet?.balance || '00' }}</span>
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

const wallet = ref(null);
const totalBalance = ref(0);
const isLoading = ref(false);

const fetchWallets = async () => {
  try {
    isLoading.value = true;
    const response = await axios.get(route('Home'));
    wallet.value = response.data.wallet;
    totalBalance.value = response.data.totalBalance;
  } catch (error) {
    console.error('Error fetching wallets:', error);
  } finally {
    isLoading.value = false;
  }
};

onMounted(fetchWallets);
</script>