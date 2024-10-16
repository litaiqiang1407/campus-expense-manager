<template>
    <div class="bg-white mt-[4px]">
        <div class="flex justify-between px-4 py-2">
            <h1 class="text-[12px]  text-secondaryText">Inflow</h1>
            <h2 class="text-[12px]  text-blueText">{{ inflow }}</h2>
        </div>
        <div class="flex justify-between px-4 py-1">
            <h1 class="text-[12px] text-secondaryText">Outflow</h1>
            <h2 class="text-[12px] text-redText">{{ outflow }}</h2>
        </div>
        <div class="flex justify-end px-4 py-1 relative">
            <p class="text-[14px] relative z-10 font-[500]">
                {{ totalFlow }}
            </p>
            <div class="absolute top-0 right-0 h-px w-1/2 bg-secondaryText mx-4"></div>
        </div>
        <div class="flex justify-center items-center py-1">
            <div class="bg-primary/10 w-3/5 flex justify-center items-center rounded-[25px]">
                <p class="text-[12px] text-primary m-2">
                    View report for this period
                </p>
            </div>
        </div>
    </div>
    <div class="m-0 mt-2 p-0">
        <div v-for="group in groupedTransactions" :key="group.date" class="my-4 bg-white">
            <div class="flex items-center justify-between px-4 py-2 relative">
                <div class="flex items-start space-x-2">
                    <div class="flex flex-row items-start">
                        <h1 class="text-3xl">{{ formatDay(new Date(group.date).getDate()) }}</h1>
                        <div class="flex flex-col items-start pt-1 pl-2">
                            <p class="text-[10px]">{{ formatDate(group.date) }}</p>
                            <p class="text-[10px]">{{ new Date(group.date).toLocaleString('en-US', {
                                month: 'long',
                                year: 'numeric'
                            }) }}</p>
                        </div>
                    </div>
                </div>
                <p class="text-[14px] font-[500]">{{ calculateTotalFlow(group.transactions) }}</p>
            </div>
            <div class="px-4">
                <span class="block w-full h-px bg-[#A7A7A7] mx-auto"></span>
            </div>
            <div v-for="transaction in group.transactions" :key="transaction.id"
                class="flex items-center px-4 py-2 relative">
                <img :src="transaction.iconPath" alt="Icon" class="w-8 h-8">
                <div class="flex justify-between w-full ml-2">
                    <span class="font-semibold text-[14px]">{{ transaction.iconName }}</span>
                    <span :class="transaction.type === 'income' ? 'text-blue-500' : 'text-red-500'">{{
                        transaction.amount }}</span>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, watch } from 'vue';

const props = defineProps({
    transactions: Array
});

const inflow = ref(0);
const outflow = ref(0);
const totalFlow = ref(0);
const dayUse = ref('');
const currentMonth = ref('');
const groupedTransactions = ref([]);
const today = new Date();
console.log(today)
const parseDate = (dateString) => {
    const isoFormatted = dateString.replace(" ", "T");
    return new Date(isoFormatted);
};

const formatDay = (day) => {
    return day < 10 ? `0${day}` : day.toString();
};
const formatDate = (dateString) => {
    const today = new Date();

    console.log(today)

    const date = new Date(dateString);

    const isToday = today.toDateString() === date.toDateString();

    const yesterday = new Date();
    yesterday.setDate(today.getDate() - 1);
    const isYesterday = yesterday.toDateString() === date.toDateString();

    const timeDiff = today.getTime() - date.getTime();
    const daysDiff = timeDiff / (1000 * 3600 * 24);

    const isThisWeek = daysDiff <= 7 && today.getDay() >= date.getDay();

    if (isToday) {
        return "Today";
    } else if (isYesterday) {
        return "Yesterday";
    } else if (isThisWeek) {
        return date.toLocaleDateString('en-US', { weekday: 'long' });
    } else {
        return date.toLocaleDateString('en-US', { day: 'numeric', month: 'long', year: 'numeric' });
    }
};

const calculateTotalFlow = (transactions) => {
    return transactions.reduce((total, transaction) => {
        return total + (transaction.type === 'income' ? transaction.amount : -transaction.amount);
    }, 0);
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

watch(
    () => props.transactions,
    (newTransactions) => {
        if (newTransactions.length > 0) {
            const transactionsByDate = {};
            newTransactions.forEach(transaction => {
                const date = parseDate(transaction.date).toDateString();
                if (!transactionsByDate[date]) {
                    transactionsByDate[date] = [];
                }
                transactionsByDate[date].push(transaction);
            });

            groupedTransactions.value = Object.keys(transactionsByDate)
                .sort((a, b) => new Date(b) - new Date(a))
                .map(date => ({
                    date,
                    transactions: transactionsByDate[date]
                }));

            calculateInflowAndOutflow(newTransactions);

            const firstTransactionDate = parseDate(newTransactions[0].date);
            if (!isNaN(firstTransactionDate.getTime())) {
                dayUse.value = firstTransactionDate.getDate();
                currentMonth.value = firstTransactionDate.toLocaleString('en-US', { month: 'long' });
            }
        }
    },
    { immediate: true }
);
</script>
