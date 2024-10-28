<template>
    <!-- Loading state -->
    <div v-if="isLoading" class="min-h-screen bg-white">
        <Loading />
    </div>

    <!-- No data state -->
    <div v-else-if="!hasData">
        <NoData message="You don't have any transactions yet" :action="true" :actionText="'Create a transaction'" :destinationPage="'CreateTransaction'" />
    </div>

    <!-- Data loaded state -->
    <div v-else class="flex flex-col">
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
        <main class="bg-primaryBackground">
            <div>
                <UseSage :transactions="transactions" :inflow="inflow" :outflow="outflow" :totalFlow="totalFlow" />
            </div>
        </main>
    </div>
</template>

<script setup>
import NoData from '../../Components/NoData/Index.vue';
import { ref, onMounted } from 'vue';
import { UseSage, Header } from '@/Pages/Transaction/Components/Index.js';
import Loading from '@/Components/Loading/Index.vue';
import axios from 'axios';

const transactions = ref([]);
const inflow = ref(0);
const outflow = ref(0);
const totalFlow = ref(0);
const hasData = ref(false); 
const selectedMonth = ref('this');
const wallets = ref([]);
const isLoading = ref(false);

const fetchTransactions = async () => {
    try {
        isLoading.value = true;
        const response = await axios.get(route('Transaction'));
        transactions.value = response.data.transactions;
        wallets.value = response.data.wallets;
        hasData.value = transactions.value.length > 0;
        calculateInflowAndOutflow(transactions.value);
    } catch (error) {
        console.error(error);
    } finally {
        isLoading.value = false;
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
    fetchTransactions();
});
</script>
