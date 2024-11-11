<template>
    <div v-if="isLoading" class="w-full h-screen flex items-center justify-center">
        <Loading class="size-8" />
    </div>

    <div v-else class="flex flex-col">
        <Header :totalFlow="totalFlow" :wallets="wallets" @walletSelected="handleWalletSelected" />


        <div class="overflow-x-auto w-full pb-2 scroll-container" ref="scrollContainer">
            <!-- Sử dụng justify-between hoặc justify-evenly để phân bổ đều các tab -->
            <div class="flex justify-evenly items-center space-x-4 min-w-max snap-x snap-mandatory">
                <span v-for="month in availableMonths" :key="month.value"
                    class="text-black cursor-pointer font-medium uppercase relative pb-1 min-w-[110px] snap-start text-center"
                    :class="{ 'font-bold': selectedMonth === month.value }" @click="selectMonth(month.value)">
                    {{ month.label }}
                    <span v-if="selectedMonth === month.value"
                        class="w-full h-0.5 bg-black absolute bottom-0 left-0"></span>
                </span>

                <span
                    class="text-black cursor-pointer font-medium uppercase relative pb-1 min-w-[110px] snap-start text-center" style="margin-right:20px;"
                    :class="{ 'font-bold': selectedMonth === 'last' }" @click="selectMonth('last')">
                    LAST MONTH
                    <span v-if="selectedMonth === 'last'" class="w-full h-0.5 bg-black absolute bottom-0 left-0"></span>
                </span>

                <span
                    class="text-black cursor-pointer font-medium uppercase relative pb-1 min-w-[110px] snap-start text-center"
                    :class="{ 'font-bold': selectedMonth === 'this' }" @click="selectMonth('this')">
                    THIS MONTH
                    <span v-if="selectedMonth === 'this'" class="w-full h-0.5 bg-black absolute bottom-0 left-0"></span>
                </span>

                <span
                    class="text-black cursor-pointer font-medium uppercase relative pb-1 min-w-[110px] snap-start text-center"
                    :class="{ 'font-bold': selectedMonth === 'future' }" @click="selectMonth('future')">
                    FUTURE
                    <span v-if="selectedMonth === 'future'"
                        class="w-full h-0.5 bg-black absolute bottom-0 left-0"></span>
                </span>
            </div>
        </div>

        <main class="bg-primaryBackground">
            <div>
                <UseSage v-if="filteredTransactions.length" :transactions="filteredTransactions" />
                <NoData v-else message="You don't have any transactions yet" :action="true"
                    :actionText="'Create a transaction'" :destinationPage="'CreateTransaction'" />
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
const availableMonths = ref([]);
const scrollContainer = ref(null);
const fetchTransactions = async () => {
    try {
        isLoading.value = true;
        const response = await axios.get(route('Transaction'));
        transactions.value = response.data.transactions;
        wallets.value = response.data.wallets;

        wallets.value.sort((a, b) => {
            if (a.name === 'Total') return -1;
            if (b.name === 'Total') return 1;
            return 0;
        });

        hasData.value = transactions.value.length > 0;

        if (wallets.value.length > 0 && !selectedWallet.value) {
            selectedWallet.value = wallets.value[0];
        }

        createAvailableMonths();
        filterTransactions();
    } catch (error) {
        console.error(error);
    } finally {
        isLoading.value = false;
    }
};

const createAvailableMonths = () => {
    const months = [];
    const currentDate = new Date();

    const transactionMonths = new Set();
    transactions.value.forEach(transaction => {
        const transactionDate = new Date(transaction.date);
        const monthYear = `${transactionDate.getMonth() + 1}-${transactionDate.getFullYear()}`;
        transactionMonths.add(monthYear);
    });

    // Lấy tất cả các tháng có giao dịch từ những năm trong quá khứ
    const yearsWithTransactions = new Set();
    transactions.value.forEach(transaction => {
        const transactionDate = new Date(transaction.date);
        yearsWithTransactions.add(transactionDate.getFullYear());
    });

    // Duyệt qua các năm trong quá khứ và lấy các tháng có giao dịch
    yearsWithTransactions.forEach(year => {
        for (let month = 1; month <= 12; month++) {
            const monthYear = `${month}-${year}`;
            if (transactionMonths.has(monthYear)) {
                months.push({
                    label: `${month}-${year}`,
                    value: monthYear
                });
            }
        }
    });

    availableMonths.value = months.reverse();
};

// Lọc giao dịch theo tháng
const filterTransactions = () => {
    let filtered = transactions.value;
    if (selectedWallet.value && selectedWallet.value.name !== 'Total') {
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
        default:
            const [month, year] = selectedMonth.value.split('-');
            filtered = filtered.filter(transaction => {
                const transactionDate = new Date(transaction.date);
                return transactionDate.getMonth() + 1 == parseInt(month) && transactionDate.getFullYear() == parseInt(year);
            });
            break;
    }

    filteredTransactions.value = filtered;
    calculateInflowAndOutflow(filteredTransactions.value);
};

// Tính tổng inflow và outflow
const calculateInflowAndOutflow = (transactions) => {
    inflow.value = transactions.reduce((total, transaction) => transaction.type === 'income' ? total + transaction.amount : total, 0);
    outflow.value = transactions.reduce((total, transaction) => transaction.type === 'expense' ? total + transaction.amount : total, 0);
    totalFlow.value = inflow.value - outflow.value;
};

// Xử lý chọn tháng
const selectMonth = (month) => {
    selectedMonth.value = month;
    filterTransactions();
};

// Kiểm tra tháng hiện tại
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
    fetchTransactions().then(() => {
        if (scrollContainer.value) {
            scrollContainer.value.scrollLeft = scrollContainer.value.scrollWidth;
        }
    });
});

</script>
<style>
/* Hide scrollbar but still allow scrolling */
.scroll-container {
    overflow-x: scroll;
}

.scroll-container::-webkit-scrollbar {
    display: none;
    /* Hide the scrollbar for WebKit browsers (Chrome, Safari) */
}

.scroll-container {
    -ms-overflow-style: none;
    /* Hide scrollbar for Internet Explorer 10+ */
    scrollbar-width: none;
    /* Hide scrollbar for Firefox */
}
</style>
