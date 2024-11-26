<template>
    <div class="min-h-screen bg-primaryBackground">
        <header class="sticky h-13 flex items-center justify-between px-4 py-2 bg-white z-50">
            <div class="flex items-center">
                <h1 class="text-[20px] font-bold">Budgets</h1>
            </div>
            <div class="flex items-center space-x-4">
                <button @click="selectWallet" class="size-8 flex items-center justify-center">
                    <div v-if="isLoading" class="size-8 flex items-center justify-center">
                        <Loading class="size-8" />
                    </div>
                    <img :src="wallet?.icon_path || '/assets/img/earth.png'" alt="Wallet" class="size-8">
                </button>
                <button class="flex items-center">
                    <font-awesome-icon icon="ellipsis-vertical" class="text-[24px]" />
                </button>
            </div>
        </header>
        <div v-if="isLoading" class="h-screen w-screen flex items-center justify-center">
            <Loading class="size-16" />
        </div>
        <div v-if="budgetList.length === 0 && !isLoading">
            <NoData message="You don't have any budgets yet" :action="true" :actionText="'Create a budget'"
                :destinationPage="'CreateBudget'" />
        </div>
        <div v-if="budgetList.length > 0 && !isLoading" class="w-full  flex flex-col items-center">
            <div class="flex items-center w-full min-w-screen overflow-x-auto bg-white mb-2">
                <div v-for="(range, index) in timeRanges" :key="index"
                    class="min-w-1/4 flex-shrink-0 pt-2 px-4 flex flex-col items-center" @click="selectRange(range)">
                    <span class="text-[12px] font-bold w-full text-center"
                        :class="{ 'text-black': activeTimeRange === range, 'text-secondaryText': activeTimeRange !== range }">
                        This {{ range }}
                    </span>
                    <div class="h-[3px] w-[90%] mt-2 rounded-t-full" :class="{ 'bg-black': activeTimeRange === range }">
                    </div>
                </div>
            </div>
            <!-- <div class="w-full bg-white p-4 mb-4">
        <p class="text-black text-left text-sm font-bold inline-block ml-2 relative">
          01/09/2024 - 31/10/2024
          <span class="absolute border-b-2 border-black w-full left-0 top-9"></span>
        </p>
      </div> -->

            <div class="bg-white p-4 rounded-none w-full max-w-full text-center mb-2">
                <div class="relative w-full h-[260px] mx-auto overflow-hidden">
                    <!-- Background circle with SVG -->
                    <svg class="w-full h-full" viewBox="0 0 100 50">
                        <path id="arcPath" d="M 3,40 A 40,40 0 0,1 97,40" fill="none" stroke="#e5e7eb" stroke-width="2"
                            stroke-linecap="round" />
                        <circle id="greenCircle" cx="3" cy="40" r="2" fill="#00BC2A" />
                        <circle id="whiteCircle" cx="3" cy="40" r="1" fill="white" />
                    </svg>

                    <!-- Budget amount in the center -->
                    <div class="absolute inset-0 flex flex-col justify-center items-center">
                        <p class="text-secondaryText text-xs mb-2">Amount you can spend</p>
                        <p class="text-primary text-2xl font-bold tracking-normal">{{ totalAmount }}</p>
                    </div>
                </div>

                <div class="flex item-center text-gray-500 mb-6 w-full">
                    <div class="text-center w-1/3  py-2">
                        <p class="text-sm font-bold">{{ formatTotalBudget(totalAmount) }}</p>
                        <p class="text-xs mt-2">Total budgets</p>
                    </div>
                    <div class="text-center w-1/3  py-2 border-l-[2px] border-r-[2px] border-primary">
                        <p class="text-sm font-bold">{{ totalSpent || '0' }}</p>
                        <p class="text-xs mt-2">Total spent</p>
                    </div>
                    <div class="text-center w-1/3  py-2">
                        <p class="text-sm font-bold">{{ remainingTime || '0' }}</p>
                        <p class="text-xs mt-2">End of period</p>
                    </div>
                </div>

                <div class="w-[240px] h-10 px-[20px] my-4 mx-auto">
                    <Add :text="'Create Budget'" :icon="''" :destinationPage="'CreateBudget'" />
                </div>
            </div>

            <div class="w-full">
                <!-- First Budget Card (Not Overspent) -->
                <div v-for="(budget, index) in timeRangeBudgetList" :index="index"
                    class="bg-white rounded-lg p-4 pb-10 mb-2 w-full">
                    <div class="flex justify-start mb-2 space-x-4">
                        <div class="size-[40px]">
                            <img :src="budget?.category?.icon_path || '/assets/icon/expense/education.png'"
                                alt="Wallet Icon" class="rounded-full w-full h-full" />
                        </div>
                        <div class="flex flex-col flex-1 space-y-2">
                            <div class="flex items-start w-full">
                                <div class="flex items-center flex-1">
                                    <h3 class="text-[16px] font-bold line-clamp-1">{{ budget?.category?.name || "Budget"
                                        }}</h3>
                                </div>
                                <div class="text-right">
                                    <p class="font-bold text-[16px]">{{ budget?.amount }}</p>
                                    <p class="text-[14px] text-secondaryText">Left 3.000</p>
                                </div>
                            </div>
                            <!-- Progress Bar -->
                            <div class="w-full relative">
                                <div class="h-1.5 bg-gray-100 rounded-full w-full relative">
                                    <div class="bg-primary h-full rounded-full" :style="{ width: progressBarWidth }">
                                    </div>
                                </div>

                                <div :style="todayIndicatorStyle"
                                    class="absolute border-l border-black top-2 flex justify-center">
                                    <div class="mt-1">
                                        <span
                                            class="bg-white p-1 rounded border border-gray-200 text-black text-xs font-bold">Today</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div :style="todayIndicatorStyle" class="absolute border-l border-black top-2"></div>
                <div class="text-center mt-1">
                    <span class="bg-white p-1 rounded border border-gray-200 text-black text-xs font-bold">Today</span>
                    <div
                        class="w-0 h-0 border-l-[5px] border-l-transparent border-r-[5px] border-r-transparent border-b-[5px] border-b-white mx-auto">
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue';
import NoData from '@/Components/NoData/Index.vue';
import Loading from '@/Components/Loading/Index.vue';
import { Add } from '@/Components/Button/Index';
import { useRouter } from 'vue-router';
const router = useRouter();

const walletId = router.currentRoute.value.query.walletId;

const wallet = ref({});
const budgetList = ref([]);
const isLoading = ref(false);
const timeRanges = ref([]);

const activeTimeRange = ref('');
const timeRangeBudgetList = ref([]);
const totalAmount = ref(0);
const remainingTime = ref('');
const today = new Date();
const transactionList = ref([]);
const transactions = ref([]);

// Define reactive variables
const budgetAvailable = ref('5.000');  // Available amount
const totalBudgets = ref('+500 K');     // Total budgets
const totalSpent = ref('0');            // Total spent
const endOfPeriod = ref('2 months');    // End of period

// Define event emitter for the component
const emit = defineEmits(['budget-created']);

// Define the method to create a budget
// function createBudget() {
//   emit('budget-created', budgetAvailable.value); // Emit event with the value of budgetAvailable
// }

// Animation function for moving the circles along the arc
// function animateCircles() {
//   const greenCircle = document.getElementById('greenCircle');
//   const whiteCircle = document.getElementById('whiteCircle');
//   const path = document.getElementById('arcPath');
//   const pathLength = path.getTotalLength();
//   let startTime = null;

//   function animate(time) {
//     if (!startTime) startTime = time;
//     const progress = (time - startTime) / 3000; // Duration of 3 seconds
//     const distance = progress * pathLength;

//     if (progress < 1) {
//       const point = path.getPointAtLength(distance);
//       greenCircle.setAttribute('cx', point.x);
//       greenCircle.setAttribute('cy', point.y);

//       // Position the white circle relative to the green circle
//       whiteCircle.setAttribute('cx', point.x);
//       whiteCircle.setAttribute('cy', point.y);

//       requestAnimationFrame(animate);
//     } else {
//       const endPoint = path.getPointAtLength(pathLength);
//       greenCircle.setAttribute('cx', endPoint.x); // Final position
//       greenCircle.setAttribute('cy', endPoint.y); // Final position
//       whiteCircle.setAttribute('cx', endPoint.x);
//       whiteCircle.setAttribute('cy', endPoint.y);
//     }
//   }

//   requestAnimationFrame(animate);
// }

const fetchBudgets = async () => {
    try {
        isLoading.value = true;
        const response = await axios.get(route('Budget'), {
            params: {
                walletId: walletId,
            },
        });
        budgetList.value = response.data.budgets;
        wallet.value = response.data.wallet;
        timeRanges.value = [...new Set(budgetList.value.map(budget => budget.time_range))];
        activeTimeRange.value = timeRanges.value[0];
        transactions.value = response.data.transactions;
        transactionList.value = transactionsByRange(transactions.value, activeTimeRange.value);
        remainingTime.value = calculateRemainingTime(activeTimeRange.value);
        timeRangeBudgets()
    } catch (error) {
        console.error(error);
    } finally {
        isLoading.value = false;
    }
}

const selectWallet = () => {
    router.push({
        name: 'SelectWallet',
        query: {
            fromPage: 'Budget'
        }
    });
};
const timeRangeBudgets = () => {
    if (activeTimeRange.value) {
        timeRangeBudgetList.value = budgetList.value.filter(budget => budget.time_range === activeTimeRange.value);
        totalAmount.value = timeRangeBudgetList.value.reduce((sum, budget) => sum + budget.amount, 0).toFixed(2);
    } else {
        timeRangeBudgetList.value = budgetList.value;
        totalAmount.value = timeRangeBudgetList.value.reduce((sum, budget) => sum + budget.amount, 0).toFixed(2);
    }
};

const selectRange = (range) => {
    activeTimeRange.value = range;
    timeRangeBudgets();
    remainingTime.value = calculateRemainingTime(range);
    transactionList.value = transactionsByRange(transactions.value, activeTimeRange.value);
};

const formatTotalBudget = (num) => {
    if (num === null || num === undefined) return '0';

    const absNum = Math.abs(num);

    if (absNum >= 1e9) {
        return (num / 1e9).toFixed(1) + 'B';
    } else if (absNum >= 1e6) {
        return (num / 1e6).toFixed(1) + 'M';
    } else if (absNum >= 1e3) {
        return (num / 1e3).toFixed(1) + 'K';
    } else {
        return num.toString();
    }
};

const calculateRemainingTime = (activeRange) => {
    const today = new Date();
    let remainingTime;

    switch (activeRange) {
        case 'month':
            const endOfMonth = new Date(today.getFullYear(), today.getMonth() + 1, 0);
            remainingTime = Math.ceil((endOfMonth - today) / (1000 * 60 * 60 * 24)) + ' days';
            break;

        case 'week':
            const daysUntilSunday = 7 - today.getDay();
            remainingTime = daysUntilSunday + ' days';
            break;

        case 'quarter':
            const month = today.getMonth();
            let endOfQuarter;

            if (month < 2) {
                endOfQuarter = new Date(today.getFullYear(), 2, 31);
            } else if (month < 5) {
                endOfQuarter = new Date(today.getFullYear(), 5, 30);
            } else if (month < 8) {
                endOfQuarter = new Date(today.getFullYear(), 8, 30);
            } else {
                endOfQuarter = new Date(today.getFullYear(), 11, 31);
            }

            remainingTime = Math.ceil((endOfQuarter - today) / (1000 * 60 * 60 * 24)) + ' days';
            break;

        case 'year':
            const monthsLeft = 12 - today.getMonth() - 1;
            remainingTime = monthsLeft + ' months';
            break;

        default:
            remainingTime = null;
            break;
    }

    return remainingTime;
};

const totalParts = computed(() => {
    switch (activeTimeRange.value) {
        case 'week':
            return 7;
        case 'month':
            return new Date(today.getFullYear(), today.getMonth() + 1, 0).getDate();
        case 'quarter':
            return 90;
        case 'year':
            return 365;
        default:
            return 0;
    }
});

const todayPosition = computed(() => {
    switch (activeTimeRange.value) {
        case 'week':
            return today.getDay();
        case 'month':
            return today.getDate();
        case 'quarter':
            const startOfQuarter = new Date(today.getFullYear(), Math.floor(today.getMonth() / 3) * 3, 1);
            return Math.floor((today - startOfQuarter) / (1000 * 60 * 60 * 24)) + 1;
        case 'year':
            return Math.floor((today - new Date(today.getFullYear(), 0, 1)) / (1000 * 60 * 60 * 24)) + 1;
        default:
            return 0;
    }
});

const progressBarWidth = computed(() => {
    return (todayPosition.value / totalParts.value) * 100 + '%';
});

const todayIndicatorStyle = computed(() => {
    let position = todayPosition.value / totalParts.value * 100;
    return {
        left: position + '%',
        height: '4px',
        width: '2px',
    };
});

const transactionsByRange = (transactions, activeRange) => {
    const today = new Date();

    return transactions.filter(transaction => {
        const transactionDate = new Date(transaction.date);

        switch (activeRange) {
            case 'week':
                const startOfWeek = startOfCurrentWeek(today);
                const endOfWeek = endOfCurrentWeek(today);
                return transactionDate >= startOfWeek && transactionDate <= endOfWeek;

            case 'month':
                const startOfMonth = new Date(today.getFullYear(), today.getMonth(), 1);
                const endOfMonth = new Date(today.getFullYear(), today.getMonth() + 1, 0);
                return transactionDate >= startOfMonth && transactionDate <= endOfMonth;

            case 'quarter':
                const quarterDates = getQuarterDates(today);
                return transactionDate >= quarterDates.start && transactionDate <= quarterDates.end;

            case 'year':
                const startOfYear = new Date(today.getFullYear(), 0, 1);
                const endOfYear = new Date(today.getFullYear(), 11, 31);
                return transactionDate >= startOfYear && transactionDate <= endOfYear;

            default:
                return false;
        }
    });
};

const startOfCurrentWeek = (date) => {
    const day = date.getDay();
    const diff = date.getDate() - day + (day === 0 ? -6 : 1); // Điều chỉnh nếu ngày là Chủ Nhật
    return new Date(date.setDate(diff));
};

const endOfCurrentWeek = (date) => {
    const startOfWeek = startOfCurrentWeek(date);
    return new Date(startOfWeek.setDate(startOfWeek.getDate() + 6));
};

const getQuarterDates = (date) => {
    const currentMonth = date.getMonth();
    const quarter = Math.floor(currentMonth / 3);
    const startMonth = quarter * 3;
    const endMonth = startMonth + 2;

    return {
        start: new Date(date.getFullYear(), startMonth, 1),
        end: new Date(date.getFullYear(), endMonth + 1, 0)
    };
};

onMounted(() => {
    // animateCircles();
    fetchBudgets();
});
</script>
