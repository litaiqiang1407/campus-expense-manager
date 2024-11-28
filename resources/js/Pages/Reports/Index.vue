<template>
    <header class="sticky px-4 py-2 flex flex-col items-center bg-white">
        <div class="h-13 flex items-center w-full justify-between">
            <font-awesome-icon icon="arrow-left" class="text-[20px]" @click="goBack" />
            <div class="flex flex-col items-center">
                <span class="text-[12px]">Balance</span>
                <span class="text-[20px] font-semibold">Reports</span>
            </div>
            <button class="text-[20px] text-primary" @click="() => openSelectTimeRange = true">
                <font-awesome-icon icon="fa-regular fa-calendar" class="text-black text-[20px]" />
            </button>
        </div>
        <div class="w-full flex items-center justify-center mt-2">
            <button class="p-2 rounded-[8px] bg-gray-100 flex items-center gap-2">
                <img src="/assets/img/wallet.png" alt="Wallet" class="size-6 rounded-full" />
                <span class="text-black text-[12px] font-semibold">Select Wallet</span>
                <font-awesome-icon icon="chevron-down" />
            </button>
        </div>
        <div class="overflow-x-auto max-w-full bg-white">
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
import { ref, onMounted, computed, watch, nextTick } from 'vue';

const selectedMonth = ref('this');

const selectedTimeRange = ref('month'); // Default selection
const openSelectTimeRange = ref(false);

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

const formatDate = (date) => {
    const day = date.getDate().toString().padStart(2, '0');
    const month = (date.getMonth() + 1).toString().padStart(2, '0');
    const year = date.getFullYear();
    return `${day}/${month}/${year}`;
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

const selectedTimeRangeValue = ref(null);

const timeRangeRefs = ref([]);

const selectTimeRange = (range) => {
    selectedTimeRange.value = range.toLowerCase(); 
    const defaultValue = filteredTimeRangeValues.value[0]; 
    selectedTimeRangeValue.value = defaultValue;
    closeSelectTimeRange();
};

const filteredTimeRangeValues = computed(() => {
    const range = availableTimeRanges.value.find(r => r.label.toLowerCase() === selectedTimeRange.value);
    return range ? range.values : [];
});

const selectTimeRangeValue = (value, index) => {
    selectedTimeRangeValue.value = value;

    const timeRangeRef = timeRangeRefs.value[index];
    if (timeRangeRef) {
        // Only scroll into view if the selected item matches
        setTimeout(() => {
            timeRangeRef.scrollIntoView({
                behavior: 'smooth',
                block: 'center',
                inline: 'center'
            });
        }, 0); 
    }
};

const setFocusOnCurrentTimeRange = () => {
    const currentDate = new Date();
    
    // Xác định phạm vi thời gian hiện tại
    const currentDay = currentDate.getDate();
    const currentMonth = currentDate.getMonth();
    const currentYear = currentDate.getFullYear();
    const currentDayOfWeek = currentDate.getDay();
    
    // Kiểm tra ngày hiện tại thuộc phạm vi ngày nào
    const availableDays = availableTimeRanges.value.find(r => r.label === 'Day');
    const availableWeeks = availableTimeRanges.value.find(r => r.label === 'Week');
    const availableMonths = availableTimeRanges.value.find(r => r.label === 'Month');
    const availableQuarters = availableTimeRanges.value.find(r => r.label === 'Quarter');
    const availableYears = availableTimeRanges.value.find(r => r.label === 'Year');

    // Kiểm tra "Ngày" (Today, Yesterday...)
    if (availableDays) {
        const today = formatDate(currentDate); // Hôm nay
        selectedTimeRangeValue.value = availableDays.values.includes('Today') ? 'Today' : today;
    }

    // Kiểm tra "Tuần" (This Week, Last Week...)
    if (availableWeeks) {
        const startOfCurrentWeek = new Date(currentDate);
        startOfCurrentWeek.setDate(currentDate.getDate() - currentDayOfWeek); // Lấy ngày đầu tuần
        const endOfCurrentWeek = new Date(startOfCurrentWeek);
        endOfCurrentWeek.setDate(startOfCurrentWeek.getDate() + 6); // Lấy ngày cuối tuần
        
        const currentWeekRange = `${formatDate(startOfCurrentWeek)} - ${formatDate(endOfCurrentWeek)}`;
        selectedTimeRangeValue.value = availableWeeks.values.includes('This Week') ? 'This Week' : currentWeekRange;
    }

    // Kiểm tra "Tháng" (This Month, Last Month...)
    if (availableMonths) {
        const thisMonth = `${currentMonth + 1}/${currentYear}`;
        selectedTimeRangeValue.value = availableMonths.values.includes('This Month') ? 'This Month' : thisMonth;
    }

    // Kiểm tra "Quý" (Current Quarter)
    if (availableQuarters) {
        const quarter = Math.floor(currentMonth / 3) + 1; // Quý hiện tại
        selectedTimeRangeValue.value = `Q${quarter} ${currentYear}`;
    }

    // Kiểm tra "Năm" (Current Year)
    if (availableYears) {
        selectedTimeRangeValue.value = currentYear.toString();
    }
};

// Chạy khi component được render
onMounted(() => {
    setFocusOnCurrentTimeRange();
});
const goBack = () => {
    window.history.back()
};
</script>