<template>
    <div class="flex flex-col space-y-2">
        <div class="flex items-center justify-between">
            <span class="font-semibold text-[10px] text-secondaryText">Report this month</span>
            <button class="text-primary font-semibold text-[10px]">See report</button>
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
                        <div class="absolute top-[79%]">
                            <span class="text-primary text-[12px]">
                                Trending report
                            </span>
                        </div>                      
                    </div>
                </swiper-slide>
                <swiper-slide>
                    <div class="flex flex-col items-center pb-4">
                        <div class="flex items-center p-1 bg-[#f0f0f0] rounded-[8px] w-full my-3">
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
                        <Bar :data="barChartData" :options="barChartOptions" class="w-full h-full mb-14" />
                        <div class="absolute top-[76%]">
                            <span class="text-primary text-[12px]">
                                Spending report
                            </span>
                        </div>
                    </div>
                </swiper-slide>
            </swiper>
        </div>
    </div>
</template>

<script setup>
import { ref, watch, onMounted  } from 'vue';
import { formatBalance } from '@/Helpers/Helpers';
import { Swiper, SwiperSlide } from 'swiper/vue';
import 'swiper/css';
import 'swiper/css/navigation';
import 'swiper/css/pagination';
import 'swiper/css/scrollbar';
import { Navigation, Pagination, Scrollbar, A11y } from 'swiper/modules';

import { Line, Bar } from 'vue-chartjs';
import { Chart as ChartJS, Title, Tooltip, Legend, BarElement, LineElement, PointElement, LinearScale, CategoryScale, Filler  } from 'chart.js';

ChartJS.register(Title, Tooltip, Legend, BarElement, LineElement, PointElement, LinearScale, CategoryScale, Filler);

const modules = [Navigation, Pagination, Scrollbar, A11y]; 

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

const barChartOptions = {
    responsive: true,
    plugins: {
        legend: { display: false },
    },
    scales: {
        x: {
            type: 'category',
            ticks: { font: { size: 10 } },
        },
        y: {
            beginAtZero: true,
            ticks: { font: { size: 10 } },
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
    top: 78%;
    color: #00BC2A;
    height: 20px !important;
    margin-top: 0 !important;
}

.swiper-button-prev:after, .swiper-button-next:after {
    font-size: 12px;
}
</style>
