<template>
    <div class="flex flex-col bg-white py-3 px-4 rounded-[12px] gap-2">
        <div class="flex flex-col">
            <span class="text-[14px] text-secondaryText font-semibold">Total</span>
            <span class="text-[20px] font-bold"
                :class="[isExpense ? 'text-redText' : 'text-blueText']">
                {{ formatBalance(balance, false, false) }}
            </span>
        </div>

        <div>
            <div class="w-full">
                <Bar :data="chartData" :options="chartOptions" />
            </div>
        </div>
    </div>
</template>

<script setup>
import { computed, watch, ref } from 'vue';
import { formatBalance } from '@/Helpers/Helpers';
import dayjs from 'dayjs';
import isBetween from 'dayjs/plugin/isBetween';

dayjs.extend(isBetween);

import { Bar } from 'vue-chartjs';
import {
    Chart as ChartJS,
    Title,
    Tooltip,
    Legend,
    BarElement,
    CategoryScale,
    LinearScale,
} from 'chart.js';

ChartJS.register(Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale);

const props = defineProps({
    data: {
        type: Object,
        default: () => ({}),
    },
    timeRange: {
        type: String,
        default: 'month',
    },
});

const isExpense = ref(props.data?.expense?.transactions.length > 0);
const balance = ref(props.data?.expense?.balance || props.data?.income?.balance);
const startDate = ref(props.data?.startDate);
const endDate = ref(props.data?.endDate);
const transactions = ref(
    (props.data?.expense?.transactions.length > 0 ? props.data?.expense?.transactions : props.data?.income?.transactions) || []
);

const currentRange = ref(props.timeRange);

const labels = computed(() => {
    const start = dayjs(startDate.value);
    const end = dayjs(endDate.value);
    let generatedLabels = [];

    switch (currentRange.value) {
        case 'day':
            generatedLabels = [start.format('DD/MM')];
            break;

        case 'week':
            generatedLabels = Array.from({ length: 7 }, (_, i) =>
                start.add(i, 'day').format('DD/MM')
            );
            break;

        case 'month':
            const monthDays = end.date();
            const ranges = [
                [1, 3],
                [4, 10],
                [11, 17],
                [18, 24],
                [25, monthDays],
            ];
            generatedLabels = ranges.map(([startDay, endDay]) =>
                `${end.date(startDay).format('DD/MM')} - ${end.date(endDay).format('DD/MM')}`
            );
            break;

        case 'quarter':
            generatedLabels = Array.from({ length: 3 }, (_, i) =>
                start.add(i, 'month').format('MMM')
            );
            break;

        case 'year':
            generatedLabels = Array.from({ length: 12 }, (_, i) =>
                start.month(i).format('MMM')
            );
            break;

        default:
            generatedLabels = [];
    }

    return generatedLabels;
});

const calculateAmountForRange = (label) => {
    const dateRange = label.includes(' - ') ? label.split(' - ') : [label, label];

    const [startDay, endDay] = dateRange;
    const startStr = `${startDay.split('/').reverse().join('/')}/${dayjs(startDate.value).year()}`;
    const endStr = `${endDay.split('/').reverse().join('/')}/${dayjs(endDate.value).year()}`;

    const startRange = dayjs(startStr, 'DD/MM/YYYY');
    const endRange = dayjs(endStr, 'DD/MM/YYYY');

    const total = transactions.value.reduce((sum, transaction) => {
        const transactionDate = dayjs(transaction.date, 'DD/MM');
        if (transactionDate.isSame(startRange, 'day') || transactionDate.isBetween(startRange, endRange, null, '[]')) {
            sum += transaction.amount; 
        }
        return sum;
    }, 0);

    return total;
};

const chartData = computed(() => {
    const borderColor = isExpense.value ? '#FF6384' : '#36A2EB';  
    const backgroundColor = isExpense.value ? 'rgba(255, 99, 132, 0.2)' : 'rgba(54, 162, 235, 0.2)';  

    return {
        labels: labels.value,
        datasets: [
            {
                label: 'Transactions',
                data: labels.value.map((label) => calculateAmountForRange(label)),
                borderColor: borderColor,
                backgroundColor: backgroundColor,
                borderWidth: 2,
                fill: true,
            },
        ],
    };
});

const chartOptions = {
    responsive: true,
    plugins: {
        legend: { display: false },
    },
    scales: {
        x: {
            type: 'category',
            grid: { display: false },
        },
        y: {
            beginAtZero: true,
            position: 'left',
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

watch(
    () => props.data,
    (newVal) => {
        if (newVal) {
            startDate.value = newVal.startDate;
            endDate.value = newVal.endDate;
            transactions.value = newVal?.expense?.transactions.length > 0 ? newVal?.expense?.transactions : newVal?.income?.transactions || [];
            isExpense.value = newVal?.expense?.transactions.length > 0;   
        }
    },
    { immediate: true }
);

watch(
    () => props.timeRange,
    (newRange) => {
        currentRange.value = newRange;
    },
    { immediate: true }
);
</script>

