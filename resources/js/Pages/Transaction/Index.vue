<template>
    <div v-if="isLoading" class="w-full h-screen flex items-center justify-center">
        <Loading class="size-8"/>
    </div>

    <div v-else class="flex flex-col">
        <Header :totalFlow="totalFlow" :wallets="wallets" @walletSelected="handleWalletSelected" />

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

        <main class="bg-primaryBackground">
            <div>
                <UseSage v-if="filteredTransactions.length" :transactions="filteredTransactions" />
                <NoData v-else message="You don't have any transactions yet" :action="true" :actionText="'Create a transaction'" :destinationPage="'CreateTransaction'" />
            </div>
        </main>
    </div>
</template>

<script setup>
import NoData from '../../Components/NoData/Index.vue';
import { ref, onMounted } from 'vue';
import { UseSage, Header } from '@/Pages/Transaction/Components/Index.js';
import Loading from '@/Components/Loading/Index.vue';

const transactions = ref([]);
const filteredTransactions = ref([]);
const inflow = ref(0);
const outflow = ref(0);
const totalFlow = ref(0);
const hasData = ref(false);
const selectedMonth = ref('this');
const wallets = ref([]);
const isLoading = ref(false);
const selectedWallet = ref(null);

const fetchTransactions = async () => {
    try {
        isLoading.value = true;
        const response = await axios.get(route('Transaction'));
        transactions.value = response.data.transactions;
        wallets.value = response.data.wallets;
        hasData.value = transactions.value.length > 0;
        filterTransactions();
    } catch (error) {
        console.error(error);
    } finally {
        isLoading.value = false;
    }
};

const filterTransactions = () => {
    let filtered = transactions.value;

    if (selectedWallet.value) {
        filtered = filtered.filter(transaction => transaction.wallet_id === selectedWallet.value.id);
    }

    switch (selectedMonth.value) {
        case 'last':
            filtered = filtered.filter(transaction => isLastMonth(transaction.date));
            break;
        case 'this':
            filtered = filtered.filter(transaction => isThisMonth(transaction.date));
            break;
        case 'future':
            filtered = filtered.filter(transaction => isFuture(transaction.date));
            break;
    }

    filteredTransactions.value = filtered;
    calculateInflowAndOutflow(filteredTransactions.value);
};

const handleWalletSelected = (wallet) => {
    selectedWallet.value = wallet;
    filterTransactions();
};

const calculateInflowAndOutflow = (transactions) => {
    inflow.value = transactions.reduce((total, transaction) => transaction.type === 'income' ? total + transaction.amount : total, 0);
    outflow.value = transactions.reduce((total, transaction) => transaction.type === 'expense' ? total + transaction.amount : total, 0);
    totalFlow.value = inflow.value - outflow.value;
};

const selectMonth = (month) => {
    selectedMonth.value = month;
    filterTransactions();
};

const isThisMonth = (date) => {
    const transactionDate = new Date(date);
    const today = new Date();
    return transactionDate.getMonth() === today.getMonth() && transactionDate.getFullYear() === today.getFullYear();
};

const isLastMonth = (date) => {
    const transactionDate = new Date(date);
    const today = new Date();
    const lastMonth = new Date(today.getFullYear(), today.getMonth() - 1, 1);
    return transactionDate.getMonth() === lastMonth.getMonth() && transactionDate.getFullYear() === lastMonth.getFullYear();
};

const isFuture = (date) => {
    const transactionDate = new Date(date);
    const today = new Date();
    return transactionDate > today;
};

onMounted(() => {
    fetchTransactions();
});
</script>
