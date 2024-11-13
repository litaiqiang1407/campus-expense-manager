<template>
    <header class="h-13 px-4 py-2 flex flex-col items-center relative bg-white">
        <div
            class="balance-section flex flex-col items-center justify-center flex-1 absolute left-1/2 transform -translate-x-1/2">
            <h2 class="balance-label text-gray-400 font-bold text-center text-xs">Balance</h2>
            <p class="balance-amount text-black text-xl font-bold">{{ formatBalance(selectedWallet?.balance || 0) }}</p>
        </div>
        <div class="icons-section flex space-x-4 ml-auto">
            <span class="search-icon cursor-pointer text-black text-lg font-normal">
                <font-awesome-icon icon="magnifying-glass" />
            </span>
            <span class="menu-icon cursor-pointer text-black text-lg font-normal flex flex-col justify-center">
                <font-awesome-icon icon="ellipsis-vertical" />
            </span>
        </div>

        <div class="relative cursor-pointer transaction-type-container bg-[#F3F3F3] flex justify-center items-center p-2 max-w-max mx-auto mt-4 rounded"
            @click="toggleDropdown">
            <img :src="selectedWallet?.icon_path || '/assets/img/wallet.png'" alt="Icon" class="h-6 w-6 mr-2 rounded-full" />
            <h3 class="transaction-type text-black text-sm mr-2">{{ selectedWallet?.name || 'Select Wallet' }}</h3>
            <font-awesome-icon icon="chevron-down" />

            <div v-if="isDropdownVisible" class="absolute left-0 top-full mt-2 bg-white shadow-md rounded w-full z-10">
                <ul>
                    <li v-for="(wallet, index) in visibleWallets" :key="wallet.id" @click="selectWallet(wallet)"
                        class="cursor-pointer hover:bg-gray-200 p-2 flex items-center">
                        <img :src="wallet?.icon_path" alt="Wallet Icon" class="h-6 w-6 mr-2 rounded-full" />
                        <span class="truncate text-[12px]">{{ wallet?.name || 'Wallet' }}</span>
                    </li>

                    <li v-if="wallets.length > 5" @click="showMoreWallets"
                        class="cursor-pointer hover:bg-gray-200 p-2 flex items-center justify-center text-sm text-primary font-semibold">
                        More...
                    </li>
                </ul>
            </div>
        </div>
    </header>
</template>

<script setup>
import { ref, computed, watch } from 'vue';
import { formatBalance } from '@/Helpers/Helpers';
import { useRouter } from 'vue-router';

const router = useRouter();
const props = defineProps({
    wallets: Array,
});

const emit = defineEmits(['walletSelected']);

// Get walletId from the query string
const walletId = router.currentRoute.value.query.walletId;
console.log("walletID", walletId);

const isDropdownVisible = ref(false);

// Find the wallet based on walletId
const selectedWallet = ref(props.wallets.find(wallet => wallet.id == walletId) || props.wallets[0]);

const visibleWallets = computed(() => props.wallets.slice(0, 3));

const toggleDropdown = () => {
    isDropdownVisible.value = !isDropdownVisible.value;
};

const selectWallet = (wallet) => {
    selectedWallet.value = wallet;
    emit('walletSelected', wallet);
    isDropdownVisible.value = false;
};

const showMoreWallets = () => {
    router.push({
        name: 'SelectWallet',
        query: {
            fromPage: 'Transaction'
        }
    });
};
watch(() => router.currentRoute.value.query.walletId, (newWalletId) => {
    selectedWallet.value = props.wallets.find(wallet => wallet.id == newWalletId) || props.wallets[0];
    emit('walletSelected', wallet);
});

document.addEventListener('click', (event) => {
    if (!event.target.closest('.transaction-type-container')) {
        isDropdownVisible.value = false;
    }
});
</script>
