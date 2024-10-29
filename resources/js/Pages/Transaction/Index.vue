<template>
    <!-- Loading state -->
    <div v-if="isLoading" class="min-h-screen bg-white">
        <Loading />
    </div>

    <!-- No data state -->
    <div v-else-if="!filteredTransactions.length">
        <NoData message="You don't have any transactions yet" :action="true" :actionText="'Create a transaction'" :destinationPage="'CreateTransaction'" />
    </div>

    <!-- Data loaded state -->
    <div v-else class="flex flex-col">
        <!-- Header -->
        <Header :totalFlow="totalFlow" :wallets="wallets" @walletSelected="handleWalletSelected" />

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
        <main class="bg-primaryBackground">
            <div>
                <UseSage :transactions="filteredTransactions" :inflow="inflow" :outflow="outflow" :totalFlow="totalFlow" />
            </div>
        </main>
    </div>
</template>

<script setup>
import NoData from '../../Components/NoData/Index.vue';
import { ref, onMounted, watch } from 'vue';
import { UseSage, Header } from '@/Pages/Transaction/Components/Index.js';
import Loading from '@/Components/Loading/Index.vue';
import axios from 'axios';

const transactions = ref([]);  // Tất cả các giao dịch
const filteredTransactions = ref([]); // Giao dịch theo ví được chọn
const inflow = ref(0);
const outflow = ref(0);
const totalFlow = ref(0);
const hasData = ref(false);
const selectedMonth = ref('this');
const wallets = ref([]);
const isLoading = ref(false);
const selectedWallet = ref(null);  // Ví hiện đang được chọn

// Hàm gọi API lấy dữ liệu giao dịch
const fetchTransactions = async () => {
    try {
        isLoading.value = true;
        const response = await axios.get(route('Transaction'));
        transactions.value = response.data.transactions;
        wallets.value = response.data.wallets;
        hasData.value = transactions.value.length > 0;
        // Lọc giao dịch theo ví được chọn khi có dữ liệu
        filterTransactions();
    } catch (error) {
        console.error(error);
    } finally {
        isLoading.value = false;
    }
};

// Hàm lọc giao dịch dựa trên `wallet_id` của ví được chọn
const filterTransactions = () => {
    if (selectedWallet.value) {
        filteredTransactions.value = transactions.value.filter(transaction => transaction.wallet_id === selectedWallet.value.id);
    } else {
        filteredTransactions.value = transactions.value;
    }
    calculateInflowAndOutflow(filteredTransactions.value);
};

// Xử lý khi chọn ví từ Header
const handleWalletSelected = (wallet) => {
    selectedWallet.value = wallet;
    filterTransactions();
};

// Hàm tính dòng tiền vào và ra
const calculateInflowAndOutflow = (transactions) => {
    inflow.value = transactions.reduce((total, transaction) => {
        return transaction.type === 'income' ? total + transaction.amount : total;
    }, 0);

    outflow.value = transactions.reduce((total, transaction) => {
        return transaction.type === 'expense' ? total + transaction.amount : total;
    }, 0);

    totalFlow.value = inflow.value - outflow.value;
};

// Lắng nghe sự thay đổi của `selectedMonth` nếu cần
const selectMonth = (month) => {
    selectedMonth.value = month;
};

onMounted(() => {
    fetchTransactions();
});
</script>
