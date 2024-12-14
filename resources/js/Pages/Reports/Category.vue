<template>
    <div class="flex flex-col min-h-screen ">
        <header class="sticky px-4 py-2 flex flex-col items-center bg-white">
            <div class="h-13 flex items-center w-full justify-between">
                <div class="flex items-center gap-2">
                    <font-awesome-icon icon="arrow-left" class="text-[20px]" @click="goBack(router)" />
                    <h1 class="text-[20px] font-semibold text-black leading-6">Category report</h1>
                </div>
                <button class="text-[20px] text-primary" @click="() => openSelectTimeRange = true">
                    <font-awesome-icon icon="fa-regular fa-calendar" class="text-black text-[20px]" />
                </button>
            </div>
            <div class="w-full flex items-center justify-center mt-2">
                <button class="p-2 rounded-[8px] bg-gray-100 flex items-center gap-2" @click="goToSelectCategories">
                    <img :src="categoryIcon || '/assets/img/wallet.png'" alt="Wallet" class="size-6 rounded-full" />
                    <span class="text-black text-[12px] font-semibold">{{ selectedCategory || 'Select Category' }}</span>
                    <font-awesome-icon icon="chevron-down" class="text-[12px]" />
                </button>
            </div>
            <div class="overflow-x-auto max-w-full bg-white scrollbar-hide">
                <div class="flex justify-between items-center">
                    <div
                        v-for="(value, index) in filteredTimeRangeValues"
                        :key="index"
                        class="min-w-[110px] flex-shrink-0 pt-2 px-4 flex flex-col items-center"
                        @click="selectTimeRangeValue(value, index)"
                        ref="timeRangeRefs">
                        <span
                            class="text-[12px] font-bold w-full text-center"
                            :class="{
                                'text-black': selectedTimeRangeValue === value,
                                'text-secondaryText': selectedTimeRangeValue !== value
                            }">
                            {{ value }}
                        </span>
                        <div
                            class="h-[3px] w-[90%] mt-2 rounded-t-full transition-all duration-300 ease-in-out"
                            :style="{
                                transform: `translateX(${selectedTimeRangeValue === value ? 0 : -100}%)`,
                                backgroundColor: selectedTimeRangeValue === value ? 'black' : 'transparent'
                            }">
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <div class="bg-white flex flex-col gap-4 mt-1 flex-1">
            <NoData v-if="isNoData" :message="'No data to display'"/>
            <Chart v-else :data="dataChart" :timeRange="selectedTimeRange"/>
        </div>
    </div>

    <div 
        v-if="openSelectTimeRange" 
        class="fixed inset-0 bg-black bg-opacity-50 flex items-end justify-center z-50" 
        @click.self="closeSelectTimeRange">
        <div class="bg-white w-full max-w-md p-4 rounded-t-lg">
            <h3 class="text-lg font-bold text-center">Select Time Range</h3>
            <div class="flex flex-col gap-2">
                <button 
                    v-for="option in timeRangeOptions" 
                    :key="option.value" 
                    class="py-2 px-4 flex items-center justify-between"           
                    @click="selectTimeRange(option.value)">
                <div class="flex items-center gap-8">
                    <font-awesome-icon :icon="option.icon" class="text-[16px]"/>
                    <span class="text-[16px]" 
                        :class="[selectedTimeRange === option.value ? 'font-bold' : 'font-semibold']">
                        {{ option.label }}
                    </span>
                </div>
                <font-awesome-icon 
                    v-if="selectedTimeRange === option.value" 
                    icon="check" 
                    class="text-primary text-[20px]"/>
                </button>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, computed, nextTick, watch } from 'vue';
import dayjs from 'dayjs';
import { useRouter } from 'vue-router'; 
import Chart from './Components/BarChart.vue';
import NoData from '@/Components/NoData/Index.vue';
import { goBack } from '@/Helpers/Helpers';

const router = useRouter();

const getLocalStorageItem = (key, defaultValue = null) => {
    const item = localStorage.getItem(key);
    try {
        return item ? JSON.parse(item) : defaultValue;
    } catch (error) {
        return item || defaultValue;
    }
};

const selectedTimeRange = ref('month');
const selectedCategory = ref(getLocalStorageItem('selectedCategory', null));
const categoryID = ref(getLocalStorageItem('categoryId', null));
const categoryIcon = ref(getLocalStorageItem('CategoryIcon', null));

const openSelectTimeRange = ref(false);

const startDate = ref(null);
const endDate = ref(null);
const currentDate = new Date();

const firstDayOfMonth = new Date(currentDate.getFullYear(), currentDate.getMonth(), 1);
const lastDayOfMonth = new Date(currentDate.getFullYear(), currentDate.getMonth() + 1, 0);

startDate.value = firstDayOfMonth.toISOString().split('T')[0]; 
endDate.value = lastDayOfMonth.toISOString().split('T')[0];   

const dataChart = ref({});
const isNoData = ref(!dataChart.value?.income?.balance && !dataChart.value?.expense?.balance);

const timeRangeOptions = [
    { label: 'Day', icon: 'calendar-day', value: 'day' },
    { label: 'Week', icon: 'calendar-week', value: 'week' },
    { label: 'Month', icon: 'calendar-days', value: 'month' },
    { label: 'Quarter', icon: 'table-cells-large', value: 'quarter' },
    { label: 'Year', icon: 'calendar', value: 'year' },
    { label: 'All', icon: 'sun', value: 'all' },
];

const closeSelectTimeRange = () => {
    openSelectTimeRange.value = false;
};

const goToSelectCategories = () => {
    router.push({
        name: 'SelectCategories',
        query: {
            fromPage: 'CategoryReport'
        }
    });
};

const formatDate = (date) => {
    const day = date.getDate().toString().padStart(2, '0');
    const month = (date.getMonth() + 1).toString().padStart(2, '0');
    const year = date.getFullYear();
    return `${day}/${month}/${year}`;
};

const updateDateRange = (value) => {
    const currentDate = new Date();

    const toISOStringDate = (date) => date.toISOString().split('T')[0];

    const handleSpecialDates = (specialDate) => {
        const date = new Date();
        if (specialDate === 'Yesterday') {
            date.setDate(date.getDate() - 1);
        }
        return {
            startDate: toISOStringDate(date),
            endDate: toISOStringDate(date),
        };
    };

    const handleWeekRange = (weekType) => {
        const date = new Date();
        let startOfWeek, endOfWeek;
        if (weekType === 'This Week') {
            startOfWeek = new Date(date);
            startOfWeek.setDate(date.getDate() - date.getDay());
            endOfWeek = new Date(startOfWeek);
            endOfWeek.setDate(startOfWeek.getDate() + 6);
        } else if (weekType === 'Last Week') {
            startOfWeek = new Date(date);
            startOfWeek.setDate(date.getDate() - date.getDay() - 7);
            endOfWeek = new Date(startOfWeek);
            endOfWeek.setDate(startOfWeek.getDate() + 6);
        }
        return {
            startDate: toISOStringDate(startOfWeek),
            endDate: toISOStringDate(endOfWeek),
        };
    };

    const handleMonthRange = (monthType) => {
        const date = new Date();
        let startOfMonth, endOfMonth;
        if (monthType === 'This Month') {
            startOfMonth = new Date(date.getFullYear(), date.getMonth(), 1);
            endOfMonth = new Date(date.getFullYear(), date.getMonth() + 1, 0);
        } else if (monthType === 'Last Month') {
            startOfMonth = new Date(date.getFullYear(), date.getMonth() - 1, 1);
            endOfMonth = new Date(date.getFullYear(), date.getMonth(), 0);
        }
        return {
            startDate: toISOStringDate(startOfMonth),
            endDate: toISOStringDate(endOfMonth),
        };
    };

    switch (selectedTimeRange.value) {
        case 'day': {
            if (value === 'Today' || value === 'Yesterday') {
                const { startDate: sd, endDate: ed } = handleSpecialDates(value);
                startDate.value = sd;
                endDate.value = ed;
            } else {
                const date = value.split('/').reverse().join('/');
                const selectedDate = dayjs(date, 'DD/MM/YYYY'); 
                startDate.value = selectedDate.format('YYYY-MM-DD');
                endDate.value = selectedDate.format('YYYY-MM-DD');
            }
            break;
        }

        case 'week': {
            if (value === 'This Week' || value === 'Last Week') {
                const { startDate: sd, endDate: ed } = handleWeekRange(value);
                startDate.value = sd;
                endDate.value = ed;
            } else {
                const [start, end] = value.split(' - ').map(date => new Date(date.split('/').reverse().join('/')));
                startDate.value = toISOStringDate(start);
                endDate.value = toISOStringDate(end);
            }
            break;
        }

        case 'month': {
            if (value === 'This Month' || value === 'Last Month') {
                const { startDate: sd, endDate: ed } = handleMonthRange(value);
                startDate.value = sd;
                endDate.value = ed;
            } else {
                const [month, year] = value.split('/').map(Number);
                const firstDay = new Date(year, month - 1, 1);
                const lastDay = new Date(year, month, 0);
                startDate.value = toISOStringDate(firstDay);
                endDate.value = toISOStringDate(lastDay);
            }
            break;
        }

        case 'quarter': {
            const quarter = Number(value[1]);
            const startMonth = (quarter - 1) * 3;
            const firstDay = new Date(currentDate.getFullYear(), startMonth, 1);
            const lastDay = new Date(currentDate.getFullYear(), startMonth + 3, 0);
            startDate.value = toISOStringDate(firstDay);
            endDate.value = toISOStringDate(lastDay);
            break;
        }

        case 'year': {
            const firstDay = new Date(value, 0, 1);
            const lastDay = new Date(value, 11, 31);
            startDate.value = toISOStringDate(firstDay);
            endDate.value = toISOStringDate(lastDay);
            break;
        }

        case 'all': {
            startDate.value = null;
            endDate.value = null;
            break;
        }
    }
};

const availableTimeRanges = computed(() => {
    const currentDate = new Date();
    const currentYear = currentDate.getFullYear();
    const currentMonth = currentDate.getMonth(); 
    const availableTimeRangesArray = [];

    const days = [];
    for (let i = 29; i >= 0; i--) {
        const date = new Date();
        date.setDate(date.getDate() - i);
        days.push(
            i === 1 ? 'Yesterday' :
            i === 0 ? 'Today' :
            formatDate(date) 
        );
    }
    availableTimeRangesArray.push({ label: 'Day', values: days });

    const weeks = [];
    
    const startOfCurrentWeek = new Date();
    startOfCurrentWeek.setDate(startOfCurrentWeek.getDate() - startOfCurrentWeek.getDay()); 
    const startOfLastWeek = new Date(startOfCurrentWeek);
    startOfLastWeek.setDate(startOfCurrentWeek.getDate() - 7); 

    for (let i = 9; i >= 0; i--) {
        const startOfWeek = new Date();
        startOfWeek.setDate(startOfWeek.getDate() - startOfWeek.getDay() - i * 7);
        const endOfWeek = new Date(startOfWeek);
        endOfWeek.setDate(startOfWeek.getDate() + 6);
        weeks.push(
            `${formatDate(startOfWeek)} - ${formatDate(endOfWeek)}` 
        );
    }

    if (weeks.length > 1) {
        weeks[weeks.length - 1] = 'This Week'; 
        weeks[weeks.length - 2] = 'Last Week'; 
    }
    availableTimeRangesArray.push({ label: 'Week', values: weeks });

    const months = [];
    
    for (let i = 0; i < 12; i++) {
        const date = new Date(currentYear, currentMonth - i, 1);
        const month = date.getMonth() + 1;
        const year = date.getFullYear();
        months.push(`${month < 10 ? '0' + month : month}/${year}`); 
    }

    months.reverse();

    if (months.length > 1) {
        months[months.length - 1] = 'This Month'; 
        months[months.length - 2] = 'Last Month'; 
    }
    
    availableTimeRangesArray.push({ label: 'Month', values: months });

    const quarters = [];
    for (let i = 1; i <= 4; i++) {
        quarters.push(`Q${i} ${currentYear}`);
    }
    availableTimeRangesArray.push({ label: 'Quarter', values: quarters });

    const years = [];
    for (let i = 0; i < 10; i++) {
        years.push((currentYear - i).toString());
    }
    years.reverse();
    availableTimeRangesArray.push({ label: 'Year', values: years });

    availableTimeRangesArray.push({ label: 'All', values: ['All Transactions'] });

    return availableTimeRangesArray;
});

const selectedTimeRangeValue = ref('This Month');

const timeRangeRefs = ref([]);

const selectTimeRange = async (range) => {
    selectedTimeRange.value = range.toLowerCase(); 
    if (selectedTimeRange.value === 'month' && !selectedTimeRangeValue.value) {
        selectedTimeRangeValue.value = 'This Month';
    } else {
        const defaultValue = filteredTimeRangeValues.value[filteredTimeRangeValues.value.length - 1];
        selectedTimeRangeValue.value = defaultValue;
    }
    if (selectedTimeRange.value === 'all') {
        startDate.value = null;
        endDate.value = null;
    } else {
        updateDateRange(selectedTimeRangeValue.value);
    }
    fetchReports(startDate.value, endDate.value, categoryID.value);
    closeSelectTimeRange();

    await nextTick();

    const index = filteredTimeRangeValues.value.findIndex((value) => value === selectedTimeRangeValue.value);
    if (index !== -1) {
        const timeRangeRef = timeRangeRefs.value[index];
        if (timeRangeRef) {
            timeRangeRef.scrollIntoView({
                behavior: 'smooth',
                block: 'center',
                inline: 'center',
            });
        }
    }
};

const filteredTimeRangeValues = computed(() => {
    const range = availableTimeRanges.value.find(r => r.label.toLowerCase() === selectedTimeRange.value);
    return range ? range.values : [];
});

const selectTimeRangeValue = (value, index) => {
    selectedTimeRangeValue.value = value;

    updateDateRange(value);
    fetchReports(startDate.value, endDate.value, categoryID.value);
    const timeRangeRef = timeRangeRefs.value[index];
    if (timeRangeRef) {
        setTimeout(() => {
            timeRangeRef.scrollIntoView({
                behavior: 'smooth',
                block: 'center',
                inline: 'center'
            });
        }, 0); 
    }
};

const fetchReports = async (startDate = null, endDate = null, categoryId = null) => {
    try {
        const response = await axios.get(route('CategoryReport'), {
            params: {  
                startDate, 
                endDate,
                categoryId  
            },
        });
        dataChart.value = response.data.reports;
        isNoData.value = !dataChart.value?.income?.balance && !dataChart.value?.expense?.balance;
    } catch (error) {
        console.error('Error fetching report trending:', error);
    };
};

onMounted(async () => {
    selectedTimeRangeValue.value = 'This Month';
    await nextTick(); 
    const index = filteredTimeRangeValues.value.findIndex(v => v === 'This Month');
    if (index !== -1) {
        const timeRangeRef = timeRangeRefs.value[index];
        if (timeRangeRef) {
            timeRangeRef.scrollIntoView({
                behavior: 'smooth',
                block: 'center',
                inline: 'center',
            });
        }
    }
    fetchReports(startDate.value, endDate.value, categoryID.value);
});

watch(isNoData, (value) => {
    isNoData.value = value;
});
</script>