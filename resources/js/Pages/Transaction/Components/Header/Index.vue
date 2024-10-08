<template>
    <header class="h-13 px-4 py-2 flex flex-col items-center relative bg-white">
        <!-- Header Top -->
        <div class="balance-section flex flex-col items-center justify-center flex-1 absolute left-1/2 transform -translate-x-1/2">
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
        <Dropdown
            :itemList="wallets"
            :selectedItem="selectedWallet"
            @update:selectedItem="selectWallet"
            defaultText="Select Wallet"
            class=" w-full max-w-max"
        />
    </header>
</template>

<script setup>
import { ref, watch } from 'vue';
import Dropdown from '@/Components/Dropdown/Index.vue';

const props = defineProps({
    totalFlow: Number,
    wallets: Array
});

const balance = ref(0);
const selectedWallet = ref(props.wallets.length > 0 ? props.wallets[0] : null);

watch(() => props.totalFlow, (newTotalFlow) => {
    balance.value = newTotalFlow;
}, { immediate: true });

const selectWallet = (wallet) => {
    selectedWallet.value = wallet;
};
</script>
