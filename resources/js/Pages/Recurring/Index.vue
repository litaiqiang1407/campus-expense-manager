<template>
    <div v-if="loading" class="w-full h-screen flex items-center justify-center">
        <Loading class="size-8" />
    </div>
    <div v-else class="bg-primaryBackground min-h-screen flex flex-col">
        <!-- Header -->
        <div class="flex justify-between items-center bg-white p-4 pb-14 border-b border-gray-300">
            <font-awesome-icon icon="arrow-left" class="text-[20px]" @click="goBack" />
            <h1 class="text-lg font-bold mr-auto ml-4">Recurring Transactions</h1>
            <div class="flex items-center space-x-4">
                <button @click="goPage('SelectWallet')">
                    <img :src="wallet?.walletIcon || '/assets/img/earth.png'" alt="Wallet" class="size-8">
                </button>
            </div>
        </div>
        <!-- Add Recurring Transaction Button -->
        <div class="pl-4 relative -translate-y-5" @click="goPage('AddRecurringTransaction')">
            <svg width="40" height="40" xmlns="http://www.w3.org/2000/svg">
                <circle cx="20" cy="20" r="20" class="fill-primary" />
                <rect x="18" y="10" width="4" height="20" fill="white" />
                <rect x="10" y="18" width="20" height="4" fill="white" />
            </svg>
        </div>

        <!-- Main Content -->
        <main class="flex-grow bg-primaryBackground">
            <div>
                <!-- Kiểm tra danh sách filteredTransactions -->
                <template v-if="filteredTransactions && filteredTransactions.length > 0">
                    <!-- Hiển thị danh sách filteredTransactions -->
                    <div v-for="transaction in filteredTransactions" :key="transaction.id"
                        class="flex items-center px-4 py-2 relative cursor-pointer bg-white mb-2"
                        @click="transactionRecurringDetails(transaction.id)">
                        <img :src="transaction.icon_path || '/assets/img/default-icon.png'" alt="Icon" class="w-10 h-10">
                        <div class="flex flex-col ml-2 w-full">
                            <div class="flex justify-between items-center">
                                <span class="font-semibold text-[14px]">{{ transaction.category_name || 'Unknown Category' }}</span>
                                <span :class="transaction.type === 'income' ? 'text-blue-500' : 'text-red-500'">
                                    {{ transaction.amount }}
                                </span>
                            </div>
                            <span class="text-[10px] text-gray-500 mt-1">Start Day: {{ transaction.start_date || 'Unknown Start Date' }}</span>
                        </div>
                    </div>
                </template>

                <NoData class="p-4" v-else :message="'No recurring transactions found. Click below to add a new one.'"
                    :action="true" :actionText="'Add New Recurring Transaction'"
                    :destinationPage="'AddRecurringTransaction'" />
            </div>
        </main>
    </div>
</template>

<script setup>
import { computed, ref, onMounted } from 'vue';
import NoData from '../../Components/NoData/Index.vue';
import { useRouter } from 'vue-router';
import axios from 'axios';
import Loading from '@/Components/Loading/Index.vue';

// State variables
const loading = ref(true);
const transactionRecurring = ref([]);
const router = useRouter();
const wallet = ref(null);

// Function to get items from localStorage
const getLocalStorageItem = (key, defaultValue = null) => {
    const item = localStorage.getItem(key);
    try {
        return item ? JSON.parse(item) : defaultValue;
    } catch (error) {
        return item || defaultValue;
    }
};

// Fetch wallet details from localStorage
const fetchWalletDetails = () => {
    wallet.value = {
        walletId: getLocalStorageItem('wallet_id', null),
        selectedWallet: getLocalStorageItem('selectedWallet', 'Default Wallet'),
        walletIcon: getLocalStorageItem('WalletIcon', '/assets/img/earth.png'),
    };
};

// Fetch recurring transactions
const fetchTransactionRecurring = async () => {
    try {
        loading.value = true;
        const response = await axios.get(route('TransactionRecurring'));
        transactionRecurring.value = response.data.transactionsRecurring || [];
        console.log(transactionRecurring.value);
    } catch (error) {
        console.error('Error fetching transaction recurring:', error);
    } finally {
        loading.value = false;
    }
};

// Computed property to filter transactions by wallet
const filteredTransactions = computed(() => {
    if (!wallet.value?.walletId || wallet.value.selectedWallet === 'Total') {
        // Show all transactions if no wallet is selected
        return transactionRecurring.value;
    }
    // Filter transactions by wallet_id
    return transactionRecurring.value.filter(
        (transaction) => transaction.wallet_id === wallet.value.walletId
    );
});

// Navigate to a page
const goPage = (page) => {
    router.push({
        name: page,
        query: { fromPage: 'RecurringTransaction' },
    });
};

const transactionRecurringDetails = (id) => {
    localStorage.clear();
    router.push({ name: 'TransactionRecurringDetails', params: { id } });
};

const goBack = () => {
    window.history.back();
};

// Lifecycle hook
onMounted(() => {
    fetchWalletDetails();
    fetchTransactionRecurring();
});
</script>
