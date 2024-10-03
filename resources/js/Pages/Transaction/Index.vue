<template>
    <div class="flex flex-col h-screen">
        <!-- Header -->
        <header class="h-20 px-4 py-2 flex flex-col items-center relative bg-white">
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
            <div
                class="cursor-pointer transaction-type-container bg-[#F3F3F3] flex justify-center items-center p-2 max-w-max mx-auto mt-4 rounded">
                <!-- Sử dụng lớp rounded -->
                <img src="/assets/icon/wallet.png" alt="Wallet" class="h-6 w-6 mr-2 rounded-full" />
                <h3 class="transaction-type text-black text-lg mr-2">Cash</h3>
                <font-awesome-icon icon="chevron-down" />
            </div>
        </header>

        <!-- History Management -->
        <div class="flex justify-between items-center m-0 pt-5 w-full max-w-md mx-auto px-4 bg-white">
            <span class="text-black cursor-pointer font-medium uppercase relative pb-1" @click="selectMonth('last')">
                LAST MONTH
                <span v-if="selectedMonth === 'last'" class="w-full h-0.5 bg-black absolute bottom-0 left-0"></span>
            </span>
            <span class="text-black cursor-pointer font-medium uppercase relative pb-1" @click="selectMonth('this')">
                THIS MONTH
                <span v-if="selectedMonth === 'this'" class="w-full h-0.5 bg-black absolute bottom-0 left-0"></span>
            </span>
            <span class="text-black cursor-pointer font-medium uppercase relative pb-1" @click="selectMonth('future')">
                FUTURE
                <span v-if="selectedMonth === 'future'" class="w-full h-0.5 bg-black absolute bottom-0 left-0"></span>
            </span>
        </div>

        <!-- Main Content -->
        <main class="bg-[#EFFBFF] flex flex-1 items-center justify-center">
            <div v-if="!hasData">
                <NoData message="Tap + to add one" />
            </div>
            <div v-else>
                <p>Your transaction data will appear here.</p>
            </div>
        </main>

        <!-- Footer -->
        <!-- <footer class="flex flex-col items-center">
            <span class="text-xs">Version 1</span>
        </footer> -->
    </div>
</template>

<script setup>
import { useRouter } from 'vue-router';
import NoData from '../../Components/NoData/Index.vue';
import { ref } from 'vue';

const hasData = ref(false);
const router = useRouter();
const balance = '$0';
const selectedMonth = ref('this');

const selectMonth = (month) => {
    selectedMonth.value = month;
};
</script>
