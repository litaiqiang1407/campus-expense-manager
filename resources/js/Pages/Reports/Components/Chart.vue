<template>
    <div class="flex flex-col bg-white py-4 px-3 rounded-[12px]">
        <div class="flex flex-col">
            <div class="flex items-center justify-between">
                <h3 class="text-[20px] font-bold text-black">{{ type }}</h3>
                <a href="#" class="text-[16px] text-primary font-semibold" @click="showPopup">See details</a>
            </div>
            <span class="text-[16px] font-semibold"
                :class="{
                    'text-green-500': type === 'Income',
                    'text-red-500': type === 'Expense',
                }">
                {{ formatBalance(balance, false, false) }}
            </span>
        </div>
        <div class="mt-4 mb-2 flex items-center gap-2"> 
            <div v-for="(label, index) in labels" :key="index" class="flex items-center gap-2"> 
                <div 
                    :style="{ backgroundColor: colors[index] }" 
                    class="p-1 rounded-full flex items-center justify-center"> 
                    <img 
                        v-if="icons[index]" 
                        :src="icons[index]" 
                        alt="icon" 
                        class="w-5 h-5" 
                    /> 
                    <span 
                        v-else 
                        class="text-white text-[10px] font-bold">N/A</span> 
                </div> 
                <span class="text-[10px] font-semibold"> 
                    {{ percentages[index] }}% 
                </span> 
            </div> 
        </div>

        <div>
            <div class="w-3/5 mx-auto">
                <Pie :data="chartData" :options="chartOptions" />
            </div>
        </div>
    </div>
    <Popup ref="popup" title="Coming Soon!" message="This feature is under development." />
</template>

<script setup>
import { computed, ref } from 'vue';
import { formatBalance, goPage } from '@/Helpers/Helpers';
import Popup from '@/Components/Popup/Index.vue';
import { Pie } from 'vue-chartjs';
import {
    Chart as ChartJS,
    Title,
    Tooltip,
    Legend,
    ArcElement,
    CategoryScale,
} from 'chart.js';

ChartJS.register(Title, Tooltip, Legend, ArcElement, CategoryScale);

const props = defineProps({
    type: {
        type: String,
        default: 'Income',
    },
    balance: {
        type: Number,
        default: 0,
    },
    data: {
        type: Array,
        default: () => [],
    },
});

const popup = ref(null);

const showPopup = () => {
    popup.value.openPopup();
};

const labels = computed(() => {
    if (props.data.length === 0 || datasetData.value.every(value => value === 0)) {
        return ["No data"];
    }
    return props.data.map(item => item.category);
});

const icons = computed(() => {
    if (props.data.length === 0 || datasetData.value.every(value => value === 0)) {
        return [null]; 
    }
    return props.data.map(item => item.icon || null); 
});


const datasetData = computed(() => {
    if (props.data.length === 0 || props.data.every(item => item.balance === 0)) {
        return [1];
    }
    return props.data.map(item => item.balance);
});

const colors = computed(() => {
    if (props.data.length === 0 || datasetData.value.every(value => value === 0)) {
        return ["#d3d3d3"]; 
    }
    return props.data.map(() => getRandomColor());
});

const total = computed(() => datasetData.value.reduce((acc, value) => acc + value, 0));

const percentages = computed(() => {
    if (props.data.length === 0 || datasetData.value.every(value => value === 0)) {
        return ["100"]; 
    }
    return datasetData.value.map((value) => ((value / total.value) * 100).toFixed(0));
});

const chartData = computed(() => ({
    labels: labels.value,
    datasets: [
        {
            label: `${props.type} Breakdown`,
            data: datasetData.value, 
            backgroundColor: colors.value,
        },
    ],
}));

const chartOptions = {
    responsive: true,
    plugins: {
        legend: {
            display: false, 
        },
        tooltip: {
            callbacks: {
                label: (context) => {
                    const value = context.raw;
                    const percentage = ((value / total.value) * 100).toFixed(2); 
                    return `${context.label}: ${value} (${percentage}%)`;
                },
            },
        },
    },
};


function getRandomColor() {
    const letters = '0123456789ABCDEF';
    let color = '#';
    for (let i = 0; i < 6; i++) {
        color += letters[Math.floor(Math.random() * 16)];
    }
    return color;
}
</script>