<template>
    <div class="flex flex-col h-screen">
        <!-- Header -->
        <Header :totalFlow="totalFlow" :wallets="wallets" />
        <!-- History Management -->
        <div class="flex justify-between items-center m-0 pt-2 w-full max-w mx-auto px-4 bg-white text-sm">
            <!-- Month Selection -->
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
        <main class="bg-primaryBackground min-h-screen">
            <div v-if="!hasData">
                <NoData message="Tap + to add one" />
            </div>
            <div v-else>
                <UseSage :transactions="transactions" :inflow="inflow" :outflow="outflow" :totalFlow="totalFlow" />
            </div>
        </main>
    </div>
</template>

<script setup>
import NoData from '../../Components/NoData/Index.vue';
import { ref, onMounted } from 'vue';
import { UseSage, Header } from '@/Pages/Transaction/Components/Index.js';
import axios from 'axios';

const transactions = ref([]);
const inflow = ref(0);
const outflow = ref(0);
const totalFlow = ref(0);
const hasData = ref(false);
const selectedMonth = ref('this');
const wallets = ref([]);

const fetchTransactions = async () => {
    try {
        const response = await axios.get(route('Transaction'));
        transactions.value = response.data;
        console.log('Fetched Transactions:', transactions.value);
        hasData.value = transactions.value.length > 0;
        calculateInflowAndOutflow(transactions.value);
    } catch (error) {
        console.error('Error fetching Transactions:', error);
    }
};

const selectMonth = (month) => {
    selectedMonth.value = month;
};

const calculateInflowAndOutflow = (transactions) => {
    inflow.value = transactions.reduce((total, transaction) => {
        return transaction.type === 'income' ? total + transaction.amount : total;
    }, 0);

    outflow.value = transactions.reduce((total, transaction) => {
        return transaction.type === 'expense' ? total + transaction.amount : total;
    }, 0);

    totalFlow.value = inflow.value - outflow.value;
};

onMounted(() => {
    fetchWallets();
    fetchTransactions();
});
</script>
