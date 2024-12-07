<template>
    <div class="flex flex-col space-y-2">
        <div class="flex items-center justify-between">
            <span class="font-semibold text-[10px] text-secondaryText">Report this month</span>
            <button class="text-primary font-semibold text-[10px]" @click="goToReports">See report</button>
        </div>
        <div class="p-4 rounded-lg shadow bg-white">
            <swiper
                :modules="modules"
                :slides-per-view="1"
                :space-between="50"
                :loop="false"
                pagination
                navigation
                class="w-full">
                <swiper-slide>
                    <div class="flex flex-col items-center pb-4">
                        <div class="w-full py-2 border-b-[1px] border-primary mb-4">
                            <h2 class="text-primary text-[12px] w-full text-center">
                                Trending report
                            </h2>
                        </div> 
                        <div class="flex items-center w-full mb-2 border-b-[1px] border-gray-100">
                            <div class="w-1/2 flex flex-col items-center border-b-[1px] pb-2"
                                @click="setActiveTrending('expense')"
                                :class="{'border-redText': activeTrending === 'expense'}"
                                >
                                <span class="text-secondaryText text-[12px]">Total spent</span>
                                <h2 class="text-[14px] font-semibold text-redText">
                                    {{ formatBalance(totalExpense) }}
                                </h2>
                            </div>
                            <div class="w-1/2 flex flex-col items-center border-b-[1px] pb-2"
                                @click="setActiveTrending('income')" 
                                :class="{'border-blueText' : activeTrending  === 'income'}">
                                <span class="text-secondaryText text-[12px]">Total income</span>
                                <h2 class="text-[14px] font-semibold text-blueText">
                                    {{ formatBalance(totalIncome) }}
                                </h2>
                            </div>
                        </div> 
                        <Line :data="chartData" :options="chartOptions" class="w-full h-full mb-14" />                    
                    </div>
                </swiper-slide>
                <swiper-slide>
                    <div class="flex flex-col items-center pb-4">
                        <div class="w-full py-2 border-b-[1px] border-primary mb-4">
                            <h2 class="text-primary text-[12px] w-full text-center">
                                Spending report
                            </h2>
                        </div> 
                        <div class="flex items-center p-1 bg-[#f0f0f0] rounded-[8px] w-full mb-3">
                            <button 
                                class="w-1/2 rounded-[4px] font-medium text-[10px] py-2" 
                                :class="activeSpending === 'week' ? 'bg-white' : 'bg-[#f0f0f0'" 
                                @click="setActiveSpending('week')">
                                Week
                            </button>
                            <button class="w-1/2 rounded-[4px] font-medium text-[10px] py-2" 
                            :class="activeSpending === 'month' ? 'bg-white' : 'bg-[#f0f0f0'" 
                            @click="setActiveSpending('month')">
                                Month
                            </button>
                        </div>
                        <div class="flex flex-col items-start w-full">
                            <h3 class="text-[12px] text-black text-left font-bold">
                                {{ activeSpending === 'week' ? formatBalance(spentThisWeek, false, true) : formatBalance(spentThisMonth, false, true) }}
                            </h3>
                            <div class="flex items-center gap-1">
                                <span class="text-[10px] text-secondaryText font-semibold">Total spent this {{ activeSpending }}</span>
                                <div class="flex items-center gap-0.5 text-[10px] font-semibold" :class="{
                                    'text-green-500': (activeSpending === 'week' && weekComparison < 0) || (activeSpending === 'month' && monthComparison < 0),
                                    'text-red-500': (activeSpending === 'week' && weekComparison > 0) || (activeSpending === 'month' && monthComparison > 0),
                                    'text-yellow-500': (activeSpending === 'week' && weekComparison === null) || (activeSpending === 'month' && monthComparison === null)}">
                                    <span v-if="activeSpending === 'week'">
                                        <font-awesome-icon :icon="weekComparison < 0 ? 'fa-solid fa-circle-arrow-down' : weekComparison > 0 ? 'fa-solid fa-circle-arrow-up' : 'fa-solid fa-circle-minus'" />
                                    </span>
                                    <span v-if="activeSpending === 'month'">
                                        <font-awesome-icon :icon="monthComparison < 0 ? 'fa-solid fa-circle-arrow-down' : monthComparison > 0 ? 'fa-solid fa-circle-arrow-up' : 'fa-solid fa-circle-minus'" />
                                    </span>
                                    <span>{{ activeSpending === 'week' ? Math.abs(weekComparison) : Math.abs(monthComparison) }}%</span>
                                </div>
                            </div>
                        </div>
                        <Bar :data="barChartData" :options="barChartOptions" class="w-full h-full mb-14" />
                    </div>
                </swiper-slide>
            </swiper>
            <Popup ref="popup" title="Coming Soon!" message="This feature is under development." />
        </div>
    </div>
</template>

<script setup>
import { ref, watch, onMounted  } from 'vue';
import { formatBalance, goPage } from '@/Helpers/Helpers';
import Popup from '@/Components/Popup/Index.vue';
import { Swiper, SwiperSlide } from 'swiper/vue';
import 'swiper/css';
import 'swiper/css/navigation';
import 'swiper/css/pagination';
import 'swiper/css/scrollbar';
import { Navigation, Pagination, Scrollbar, A11y } from 'swiper/modules';

import { Line, Bar } from 'vue-chartjs';
import { Chart as ChartJS, Title, Tooltip, Legend, BarElement, LineElement, PointElement, LinearScale, CategoryScale, Filler  } from 'chart.js';
import { useRouter } from 'vue-router';

ChartJS.register(Title, Tooltip, Legend, BarElement, LineElement, PointElement, LinearScale, CategoryScale, Filler);

const modules = [Navigation, Pagination, Scrollbar, A11y]; 

const router = useRouter();

const activeTrending = ref('expense');
const activeSpending = ref('month');

const reportTrending = ref({});
const dates = ref([]);

const expense = ref([]);
const totalExpense = ref(0);

const income = ref([]);
const totalIncome = ref(0);

const formattedDates = ref([]);

const weekExpense = ref([]);
const monthExpense = ref([]);

const spentThisMonth = ref(0);
const spentThisWeek = ref(0);

const monthComparison = ref(0);
const weekComparison = ref(0);

const popup = ref(null);

const showPopup = () => {
    popup.value.openPopup();
};

const goToReports = () => {
    goPage(router, 'Reports');
};

const barChartOptions = {
    responsive: true,
    plugins: {
        legend: { display: false },
    },
    scales: {
        x: {
            type: 'category',
            grid: {
                display: false, 
            },
            ticks: { 
                font: { size: 10 } },
        },
        y: {
            beginAtZero: true,
            position: 'right',
            ticks: { 
                callback: function(value) {
                    return formatBalance(value, true, false); 
                },
                font: { size: 10 } },
        },
    },
};

const barChartData = ref({
    labels:  [] ,
    datasets: [{
        label: 'Spending',
        data: [],
        backgroundColor: '#FF6384',
        borderColor: 'rgba(255, 99, 132, 0.2)',
        borderWidth: 1,
    }]
});

const chartData = ref({
    labels: [], 
    datasets: [{
        label: 'This month',
        data: [], 
        borderColor: '#FF6384', 
        backgroundColor: 'rgba(255, 99, 132, 0.2)', 
        borderWidth: 2,
        fill: true,
    }]
});

const chartOptions = {
    responsive: true,
    plugins: {
        legend: {
            display: false, 
        },
        title: {
            display: true,
            text: 'This month', 
            position: 'bottom', 
            padding: {
                top: 10, 
                bottom: 0, 
            },
            font: {
                size: 10, 
                weight: '400', 
            },
            color: '#A7A7A7', 
        },
    },
    scales: {
        x: {
            type: 'category',
            grid: {
                display: false, 
            },
            ticks: {
                callback: function(value, index, values) {
                    const labels = formattedDates.value;
                    if (index === 0 || index === labels.length - 1 || index === 29) {
                        return labels[index]; 
                    }

                    return ''; 
                },
                font: {
                    size: 10, 
                },
            },
        },
        y: {
            beginAtZero: true,
            position: 'right',
            ticks: {
                callback: function(value) {
                    return formatBalance(value, true, false);
                },
                font: {
                    size: 10, 
                },
            },
        },
    },
};

const setActiveTrending = (tab) => {
    activeTrending.value = tab;

    chartData.value = {
        labels: formattedDates.value,
        datasets: [{
            label: 'This month',
            data: tab === 'expense' ? expense.value : income.value,
            borderColor: tab === 'expense' ? '#FF6384' : '#36A2EB',
            backgroundColor: tab === 'expense' ? 'rgba(255, 99, 132, 0.2)' : 'rgba(54, 162, 235, 0.2)',
            borderWidth: 2,
            fill: true,
        }]
    };
};

const setActiveSpending = (tab) => {
    activeSpending.value = tab;
    barChartData.value = {
        labels: tab === 'week' ? ['Last week', 'This week'] : ['Last month', 'This month'],
        datasets: [{
            label: tab === 'week' ? 'Weekly Expenses' : 'Monthly Expenses',
            data: tab === 'week' ? weekExpense.value : monthExpense.value,
            borderColor: tab === 'week' ? '#36A2EB' : '#FF9F40',
            backgroundColor: tab === 'week' ? 'rgba(54, 162, 235, 0.2)' : 'rgba(255, 159, 64, 0.2)',
            borderWidth: 2,
            fill: true,
        }]
    };
};

const fetchReportTrending = async () => {
    try {
        const response = await axios.get(route('HomeReport'));
        reportTrending.value = response.data.reportTrending;
        const reportSpending = response.data.reportSpending;

        dates.value = reportTrending.value.dates;
        expense.value = reportTrending.value.expense;
        totalExpense.value = expense.value.reduce((acc, curr) => acc + curr, 0);

        income.value = reportTrending.value.income;
        totalIncome.value = income.value.reduce((acc, curr) => acc + curr, 0);

        formattedDates.value = dates.value.map(date => {
            const [day, month] = date.split('/'); 
            return `${day}/${month}`; 
        });

        weekExpense.value = reportSpending.weekExpense;
        monthExpense.value = reportSpending.monthExpense;

        spentThisMonth.value = reportSpending.monthExpense[1];
        spentThisWeek.value = reportSpending.weekExpense[1];

        weekComparison.value = reportSpending.weekComparison;
        monthComparison.value = reportSpending.monthComparison;

    } catch (error) {
        console.error('Error fetching report trending:', error);
    }
};


watch(dates, (newDates) => {
    chartData.value.labels = newDates;
});

watch(expense, (newExpense) => {
    chartData.value.datasets[0].data = newExpense;
});

onMounted(async () => {
    await fetchReportTrending(); 
    setActiveTrending('expense');
    setActiveSpending('month');
});
</script>

<style>
.swiper-pagination-bullets {
    bottom:  10px !important;
}
.swiper-pagination-bullet-active {
  background: #00BC2A !important;
}

.swiper-button-prev, .swiper-button-next {
    top: 84%;
    color: #00BC2A;
    height: 20px !important;
    margin-top: 0 !important;
}

.swiper-button-prev:after, .swiper-button-next:after {
    font-size: 12px;
}
</style>
