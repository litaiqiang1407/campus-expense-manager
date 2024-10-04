<template>
    <div class="flex flex-col h-screen">
        <!-- Header -->
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
            <div
                class="cursor-pointer transaction-type-container bg-[#F3F3F3] flex justify-center items-center p-2 max-w-max mx-auto mt-4 rounded">
                <img :src="iconImage" alt="Icon" class="h-6 w-6 mr-2 rounded-full" />
                <h3 class="transaction-type text-black text-sm mr-2">{{ iconName }}</h3>
                <font-awesome-icon icon="chevron-down" />
            </div>
        </header>

        <!-- History Management -->
        <div class="flex justify-between items-center m-0 pt-2 w-full max-w mx-auto px-4 bg-white text-sm">
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
        <main class="bg-[#EFFBFF]">
            <div v-if="!hasData">
                <NoData message="Tap + to add one" />
            </div>
            <div v-else>
                <UseSage :totalFlow="totalFlow" :dayUse="dayUse" :iconImage="iconImage" :iconName="iconName" />
            </div>
        </main>
    </div>
</template>

<script setup>
import NoData from '../../Components/NoData/Index.vue';
import { ref } from 'vue';
import { UseSage } from '@/Pages/Transaction/Components/Index.js';

defineProps(['transactions']);
const hasData = ref(true);
const balance = '0';
const selectedMonth = ref('this');
const iconImage = "/assets/img/wallet.jpg";
const iconName = 'Cash';
const dayUse = '14';
const totalFlow = '22';

const selectMonth = (month) => {
    selectedMonth.value = month;
};
</script>
