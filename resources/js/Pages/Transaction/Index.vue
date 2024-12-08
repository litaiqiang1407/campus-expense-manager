<template>
    <div v-if="isLoading" class="w-full h-screen flex items-center justify-center">
        <Loading class="size-8" />
    </div>

    <div v-else class="flex flex-col">
        <Header :totalFlow="totalFlow" :wallets="wallets" @walletSelected="handleWalletSelected" />

        <div class="overflow-x-auto max-w-full bg-white scroll-container" ref="scrollContainer">
            <div class="flex justify-between items-center">
                <div v-for="(month, index) in availableMonths" :key="month"
                    class="min-w-[110px] flex-shrink-0 pt-2 px-4 flex flex-col items-center"
                    @click="selectMonth(month, index)" ref="monthRefs">
                    <span class="text-[12px] font-bold w-full text-center" :class="{
                        'text-black': selectedMonth === month,
                        'text-secondaryText': selectedMonth !== month
                    }">
                        {{ month === 'this' ? 'THIS MONTH' : month === 'last' ? 'LAST MONTH' : month === 'future' ?
                        'FUTURE' : `${month}` }}
                    </span>

                    <div class="h-[3px] w-[90%] mt-2 rounded-t-full transition-all duration-300 ease-in-out" :style="{
                        transform: `translateX(${selectedMonth === month ? 0 : -100}%)`,
                        backgroundColor: selectedMonth === month ? 'black' : 'transparent'
                    }"></div>
                </div>
            </div>
        </div>
        <main class="bg-primaryBackground">
            <div>
                <UseSage v-if="filteredTransactions.length" :calculatedTransactions="calculatedTransactions"
                    :transactions="filteredTransactions" />
                <NoData v-else message="You don't have any transactions yet" :action="true"
                    :actionText="'Create a transaction'" :destinationPage="'AddRecurringTransaction'" />
            </div>
        </main>
    </div>
</template>

<script setup>
import NoData from '../../Components/NoData/Index.vue';
import { ref, onMounted, computed, watch, nextTick } from 'vue';
import { UseSage, Header } from '@/Pages/Transaction/Components/Index.js';
import Loading from '@/Components/Loading/Index.vue';

const transactions = ref([]);
const filteredTransactions = ref([]);
const calculatedTransactions = ref([]);
const inflow = ref(0);
const outflow = ref(0);
const totalFlow = ref(0);
const hasData = ref(false);
const selectedMonth = ref('this');
const wallets = ref([]);
const isLoading = ref(false);
const selectedWallet = ref(null);
// const availableMonths = ref([]);
const scrollContainer = ref(null);
const fetchTransactions = async () => {
    try {
        isLoading.value = true;
        const response = await axios.get(route('Transaction'));
        transactions.value = response.data.transactions;
        const recurringTransactions = response.data.calculatedTransactions;
        wallets.value = response.data.wallets;
        transactions.value = [...transactions.value, ...recurringTransactions];
        wallets.value.sort((a, b) => {
            if (a.name === 'Total') return -1;
            if (b.name === 'Total') return 1;
            return 0;
        });

        hasData.value = transactions.value.length > 0;

        if (wallets.value.length > 0 && !selectedWallet.value) {
            selectedWallet.value = wallets.value[0];
        }

        filterTransactions();
    } catch (error) {
        console.error(error);
    } finally {
        isLoading.value = false;
    }
};


const handleWalletSelected = (wallet) => {
    selectedWallet.value = wallet;
    filterTransactions();
};

const availableMonths = computed(() => {
    const currentDate = new Date();
    const currentMonth = currentDate.getMonth() + 1;
    const currentYear = currentDate.getFullYear();

    const availableMonthsArray = [];

    for (let month = 1; month < currentMonth; month++) {
        availableMonthsArray.push(`${month < 10 ? '0' + month : month}-${currentYear}`);
    }

    availableMonthsArray.push('last');
    availableMonthsArray.push('this');
    availableMonthsArray.push('future');

    return availableMonthsArray;
});

// const createAvailableMonths = () => {
//     const months = [];
//     const currentDate = new Date();

//     const transactionMonths = new Set();
//     transactions.value.forEach(transaction => {
//         const transactionDate = new Date(transaction.date);
//         const monthYear = `${transactionDate.getMonth() + 1}-${transactionDate.getFullYear()}`;
//         transactionMonths.add(monthYear);
//     });

//     // Get the current month and year for comparisons
//     const currentMonth = currentDate.getMonth();
//     const currentYear = currentDate.getFullYear();

//     // Define dates for "Last Month" and "Future"
//     const lastMonthDate = new Date(currentYear, currentMonth - 1, 1);
//     const futureDate = new Date(currentYear, currentMonth + 1, 1);

//     // Duyệt qua các năm và các tháng trong năm đó
//     transactionMonths.forEach(monthYear => {
//         const [month, year] = monthYear.split('-').map(Number);
//         const transactionDate = new Date(year, month - 1, 1);

//         // Check if the month is not in "Last Month," "This Month," or "Future"
//         if (
//             !(
//                 (transactionDate.getMonth() === lastMonthDate.getMonth() && transactionDate.getFullYear() === lastMonthDate.getFullYear()) ||
//                 (transactionDate.getMonth() === currentMonth && transactionDate.getFullYear() === currentYear) ||
//                 transactionDate > currentDate
//             )
//         ) {
//             months.push({
//                 label: `${month}-${year}`,
//                 value: monthYear
//             });
//         }
//     });

//     // Sort the months
//     availableMonths.value = months.sort((a, b) => {
//         const [monthA, yearA] = a.value.split('-').map(Number);
//         const [monthB, yearB] = b.value.split('-').map(Number);

//         if (yearA !== yearB) {
//             return yearA - yearB;
//         } else {
//             return monthA - monthB;
//         }
//     });
// };

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

    // Sort the filtered transactions by date to ensure proper grouping
    filtered.sort((a, b) => new Date(a.date) - new Date(b.date));

    filteredTransactions.value = filtered;
    calculateInflowAndOutflow(filteredTransactions.value);
};

const calculateInflowAndOutflow = (transactions) => {
    inflow.value = transactions.reduce((total, transaction) => transaction.type === 'income' ? total + transaction.amount : total, 0);
    outflow.value = transactions.reduce((total, transaction) => transaction.type === 'expense' ? total + transaction.amount : total, 0);
    totalFlow.value = inflow.value - outflow.value;
};

const monthRefs = ref([]);

const selectMonth = (month, index) => {
    selectedMonth.value = month;
    filterTransactions();

    const monthElement = monthRefs.value[index];
    if (monthElement) {
        monthElement.scrollIntoView({
            behavior: 'smooth',
            block: 'center',
            inline: 'center'
        });
    }
};

onMounted(() => {
    nextTick(() => {
        const thisMonthIndex = availableMonths.value.indexOf('this');
        if (thisMonthIndex !== -1) {
            selectedMonth.value = 'this';
            const monthElement = monthRefs.value[thisMonthIndex];
            if (monthElement) {
                monthElement.scrollIntoView({
                    behavior: 'smooth',
                    block: 'center',
                    inline: 'center'
                });
            }
        }
    });
});

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
