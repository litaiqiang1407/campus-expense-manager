<template>
    <header class="h-13 px-4 py-2 flex flex-col items-center relative bg-white">
        <!-- Header Top -->
        <div
            class="balance-section flex flex-col items-center justify-center flex-1 absolute left-1/2 transform -translate-x-1/2">
            <h2 class="balance-label text-gray-400 font-bold text-center text-xs">Balance</h2>
            <p class="balance-amount text-black text-xl font-bold">{{ balance }}</p>
        </div>
        <div class="icons-section flex space-x-4 ml-auto">
            <span class="search-icon cursor-pointer text-black text-lg font-normal">
                <font-awesome-icon icon="magnifying-glass" />
            </span>
            <span class="menu-icon cursor-pointer text-black text-lg font-normal flex flex-col justify-center">
                <font-awesome-icon icon="ellipsis-vertical" />
            </span>
        </div>

        <!-- Transaction Type Dropdown -->
        <div class="relative cursor-pointer transaction-type-container bg-[#F3F3F3] flex justify-center items-center p-2 max-w-max mx-auto mt-4 rounded"
            @click="toggleDropdown">
            <img :src="firstWallet?.icon_path || '/assets/img/wallet.png'" alt="Icon"
                class="h-6 w-6 mr-2 rounded-full" />
            <h3 class=" transaction-type text-black text-sm mr-2">{{ firstWallet?.name || 'Slect Wallet' }}</h3>
            <font-awesome-icon icon="chevron-down" />

            <!-- Dropdown -->
            <div v-if="isDropdownVisible" class="absolute left-0 top-full mt-2 bg-white shadow-md rounded w-full z-10">
                <ul>
                    <li v-for="wallet in wallets" :key="wallet.id" @click="selectWallet(wallet)"
                        class="cursor-pointer hover:bg-gray-200 p-2 flex items-center">
                        <img :src="wallet.icon_path" alt="Wallet Icon" class="h-6 w-6 mr-2 rounded-full" />
                        <span class="truncate text-[12px]">{{ wallet.name }}</span>
                    </li>
                </ul>
            </div>
        </div>
    </header>
</template>

<script setup>
import { ref, computed, watch } from 'vue';

const props = defineProps({
    totalFlow: Number,
    wallets: Array
});

const balance = ref(0);
const isDropdownVisible = ref(false);

const firstWallet = computed(() => props.wallets.length > 0 ? props.wallets[0] : null);

watch(() => props.totalFlow, (newTotalFlow) => {
    balance.value = newTotalFlow;
}, { immediate: true });

const selectWallet = (wallet) => {
    const walletIndex = props.wallets.findIndex(w => w.id === wallet.id);
    if (walletIndex !== -1) {
        props.wallets.unshift(props.wallets.splice(walletIndex, 1)[0]);
    }
    isDropdownVisible.value = false;
};

const toggleDropdown = () => {
    isDropdownVisible.value = !isDropdownVisible.value;
};

document.addEventListener('click', (event) => {
    if (!event.target.closest('.transaction-type-container')) {
        isDropdownVisible.value = false;
    }
});
</script>
