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
        <!-- Header Bottom -->
        <div class="relative cursor-pointer transaction-type-container bg-[#F3F3F3] flex justify-center items-center p-2 max-w-max mx-auto mt-4 rounded"
            @click="toggleDropdown">
            <img src="/assets/img/wallet.png" alt="Icon" class="h-6 w-6 mr-2 rounded-full" />
            <h3 class="transaction-type text-black text-sm mr-2">{{ selectedWallet?.name || 'Select Wallet' }}</h3>
            <font-awesome-icon icon="chevron-down" />

            <!-- Dropdown -->
            <div v-if="isDropdownVisible" class="absolute left-0 top-full mt-2 bg-white shadow-md rounded w-full z-10">
                <ul>
                    <li v-for="wallet in wallets" :key="wallet.id" @click="selectWallet(wallet)"
                        class="cursor-pointer hover:bg-gray-200 p-2">
                        {{ wallet.name }}
                    </li>
                </ul>
            </div>
        </div>
    </header>
</template>

<script setup>
import { ref, watch } from 'vue';

const props = defineProps({
    totalFlow: Number,
    wallets: Array
});

const balance = ref(0);
const selectedWallet = ref(props.wallets.length > 0 ? props.wallets[0] : null);
const isDropdownVisible = ref(false);

// Cập nhật balance khi totalFlow thay đổi
watch(() => props.totalFlow, (newTotalFlow) => {
    balance.value = newTotalFlow;
}, { immediate: true });

// Chọn ví và ẩn dropdown
const selectWallet = (wallet) => {
    selectedWallet.value = wallet;
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
