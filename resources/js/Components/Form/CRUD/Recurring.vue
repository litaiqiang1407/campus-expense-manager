<template>
    <div class="py-4 pl-0.5">
        <button type="button" class="flex w-full space-x-2 items-center" @click="togglePopup">
            <div v-if="iconSrc" class="w-15 flex items-center justify-start">
                <img :src="iconSrc" class="w-11 h-11" />
            </div>
            <div class="flex items-center justify-center">
                <font-awesome-icon icon="fa-regular fa-calendar" class="text-black text-[16px]" />
            </div>
            <span class="font-medium text-secondaryText pl-1">{{ repeatText }}</span>
        </button>

        <div v-if="isPopupVisible" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg shadow-lg w-auto p-4 m-4">
                <div class="relative mb-5">
                    <button type="button" @click="toggleDropdown"
                        class="w-full text-[16px] font-bold py-2 mb-4 rounded-md flex justify-between items-center">
                        {{ selectedRepeat || 'Repeat Daily' }}
                        <font-awesome-icon icon="chevron-down" />
                    </button>
                    <div class="flex justify-start items-center mb-4">
                        <h1 class="mr-2">From:</h1>
                        <div>
                            <Datepicker v-model="internalDate" :inputFormat="'dd-MM-yyyy'"
                                class="datepicker relative" />
                        </div>
                        <div class="time-picker relative cursor-pointer flex items-center -ml-14 space-x-2">
                            <h1 class=" relative cursor-pointer -ml-5">at:</h1>
                            <div @click="toggleTimePickerPopup">
                                <span class="text-primary">{{ time || "Select Time" }}</span>
                            </div>
                        </div>
                        <div v-if="isTimePickerPopupVisible"
                            class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                            <div class="bg-white p-5 rounded-lg shadow-lg w-72 m-5">
                                <h2 class="font-bold mb-4 text-center text-lg">Choose Time</h2>
                                <div
                                    class="relative w-full h-60 rounded-full border-2 border-gray-300 mx-auto mb-6 flex items-center justify-center">
                                    <div class="absolute w-full h-full flex items-center justify-center">
                                        <div v-for="hour in 12" :key="hour" :class="{
                                            'absolute': true,
                                            'text-xl': true,
                                            'font-semibold': true,
                                            'cursor-pointer': true,
                                            'hover:text-primary': selectedHour !== hour,
                                            'text-primary': selectedHour === hour
                                        }" :style="getHourPosition(hour)">
                                            <span @click="selectHour(hour)">{{ hour }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex mb-4">
                                    <div class="w-1/3 mr-2">
                                        <label for="hour" class="block text-sm font-medium text-gray-700">Hour</label>
                                        <input v-model="selectedHour" type="number" min="1" max="12"
                                            class="w-full text-center text-xl p-2.5 border border-gray-300 " readonly />
                                    </div>
                                    <div class="w-1/3 mr-2">
                                        <label for="minute"
                                            class="block text-sm font-medium text-gray-700">Minute</label>
                                        <input v-model="selectedMinute" type="number" min="0" max="59"
                                            class="w-full text-center text-xl p-2.5 border border-gray-300 " />
                                    </div>
                                    <div class="w-1/3">
                                        <label class="block text-sm font-medium text-gray-700">AM/PM</label>
                                        <div class="flex flex-col space-y-1">
                                            <button type="button" @click="selectPeriod('AM')" :class="{
                                                'bg-primary text-white': selectedPeriod === 'AM',
                                                'bg-white text-primary': selectedPeriod !== 'AM'
                                            }" class="w-full text-[14px] border">
                                                AM
                                            </button>
                                            <button type="button" @click="selectPeriod('PM')" :class="{
                                                'bg-primary text-white': selectedPeriod === 'PM',
                                                'bg-white text-primary': selectedPeriod !== 'PM'
                                            }" class="w-full text-[14px] border">
                                                PM
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex justify-center">
                                    <button @click="confirmTime"
                                        class="w-full bg-primary text-white py-2 rounded-md">Done</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <ul v-if="isDropdownOpen"
                        class="absolute left-0 top-0 w-full bg-white border border-gray-200 rounded-md shadow-lg z-10 text-left">
                        <li v-for="option in repeatOptions" :key="option" @click="selectRepeat(option)"
                            class="px-4 py-2 text-gray-600 text-[12px] hover:bg-gray-100 cursor-pointer">
                            {{ option }}
                        </li>
                    </ul>
                </div>
                <div v-if="selectedRepeat === 'Repeat Daily'" class="space-y-4">
                    <div class="flex items-center mb-4 space-x-2">
                        <h1 class="text-sm">Every:</h1>
                        <input type="number" class="w-8 text-center border-b border-b-black" min="1"
                            v-model="repeatInterval" />
                        <h1 class="text-sm">day</h1>
                    </div>
                </div>
                <div v-if="selectedRepeat === 'Repeat Weekly'" class="space-y-4">
                    <div class="flex flex-wrap space-x-2">
                        <button type="button" v-for="(day, index) in weekDays" :key="index" class="w-8 h-8 rounded-full flex items-center justify-center border-2
                      md:w-12 md:h-12 lg:w-12 lg:h-12" :class="{
                        'bg-primary text-white': selectedDays.includes(day),
                        'bg-white text-primary': !selectedDays.includes(day)
                    }" @click="toggleDay(day)">
                            {{ day.slice(0, 1) }}
                        </button>
                    </div>
                </div>
                <div v-if="selectedRepeat === 'Repeat Monthly'">
                    <div class="flex items-center mb-4 space-x-2">
                        <h1 class="text-sm">Every:</h1>
                        <input type="number" class="w-8 text-center border-b border-b-black" min="1"
                            v-model="repeatInterval" />
                        <h1 class="text-sm">month</h1>
                    </div>
                    <p class="text-sm py-2">On the same day each month ({{ currentDate }})</p>
                </div>
                <div v-if="selectedRepeat === 'Repeat Yearly'">
                    <div class="flex items-center mb-4 space-x-2">
                        <h1 class="text-sm">Every:</h1>
                        <input type="number" class="w-8 text-center border-b border-b-black" min="1"
                            v-model="repeatInterval" />
                        <h1 class="text-sm">year</h1>
                    </div>
                </div>
                <div class="relative">
                    <div class="flex space-x-2">
                        <button type="button" @click="toggleRepeatTypeDropdown"
                            class="w-auto text-[16px] py-2 mb-4 rounded-md flex justify-between items-center">
                            {{ selectedRepeatType || 'Forever' }}
                            <font-awesome-icon class="pl-2 text-[12px] text-gray-500" icon="chevron-down" />
                        </button>
                        <div v-if="selectedRepeatType === 'Untill'">
                            <div class="flex items-center space-x-2">
                                <Datepicker class="mt-1.5 text-primary" v-model="internalForDate"
                                    :inputFormat="'dd-MM-yyyy'"></Datepicker>
                            </div>
                        </div>
                        <div v-if="selectedRepeatType === 'For'" class="flex">
                            <div class="flex items-center mb-4 space-x-2">
                                <input type="number" class="w-8 text-center border-b border-b-black" min="1"
                                    v-model="times" />
                                <h1 class="text-sm">time</h1>
                            </div>
                        </div>
                    </div>
                    <ul v-if="isDropdownRepeatTypeOpen"
                        class="absolute left-0 top-0 w-full bg-white border border-gray-200 rounded-md shadow-lg z-10 text-left">
                        <li v-for="option in repeatType" :key="option" @click="selectRepeatType(option)"
                            class="px-4 py-2 text-gray-600 text-[12px] hover:bg-gray-100 cursor-pointer">
                            {{ option }}
                        </li>
                    </ul>
                </div>
                <div class="flex justify-end">
                    <button type="button" class="bg-white text-primary font-medium py-2 px-4 rounded-md"
                        @click="togglePopup">
                        CANCEL
                    </button>
                    <button type="button" class="bg-primary text-white font-medium py-2 px-4 rounded-md"
                        @click="updateRepeatText">
                        DONE
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, defineEmits, watch } from "vue";
import Datepicker from "vue3-datepicker";
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import Swal from 'sweetalert2';
import { isNumber } from "lodash";
const formatDate = (date) => {
    const day = String(date.getDate()).padStart(2, '0');
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const year = date.getFullYear();

    return `${day}/${month}/${year}`;
};
const formatDateMMDDYYY = (dateString) => {
    const [day, month, year] = dateString.split('/').map(Number);

    const date = new Date(year, month - 1, day);

    const formattedMonth = String(date.getMonth() + 1).padStart(2, '0');
    const formattedDay = String(date.getDate()).padStart(2, '0');
    const formattedYear = date.getFullYear();

    return `${formattedMonth}/${formattedDay}/${formattedYear}`;
};
const emit = defineEmits();
const currentDate = ref('');
const today = new Date();
const getLocalStorageItem = (key, defaultValue = null) => {
    const item = localStorage.getItem(key);
    try {
        return item ? JSON.parse(item) : defaultValue;
    } catch (error) {
        return item || defaultValue;
    }
};
currentDate.value = today.getDate();

const props = defineProps({
    repeatText: {
        type: String,
        default: 'No Repeat'
    },
    start_date: {
        type: String,
        default: ''
    },
    end_date: {
        type: String,
        default: ''
    },
    frequency: {
        type: String,
        default: ''
    },
    timeText: {
        type: String,
        default: ''
    },
    repeatType: {
        type: String,
        default: "Forever"
    },
    times: {
        type: String,
        default: "1"
    }
});

const isPopupVisible = ref(false);
const isDropdownOpen = ref(false);
const isDropdownRepeatTypeOpen = ref(false);
const storedTime = getLocalStorageItem('timetext', null);
const [storedHour, storedMinute, storedPeriod] = storedTime
    ? storedTime.split(/[: ]/)
    : [12, '00', 'AM'];
const selectedHour = ref(Number(storedHour));
const selectedMinute = ref(Number(storedMinute));
const selectedPeriod = ref(storedPeriod);
const timeText = computed(() => {
    const hour = selectedHour.value;
    const minute = selectedMinute.value < 10 ? '0' + selectedMinute.value : selectedMinute.value;
    const timeString = `${hour}:${minute} ${selectedPeriod.value}`;
    return timeString;
});
const selectedRepeat = ref(props.repeatText || ref(getLocalStorageItem('repeatName', 'No Repeat'), null))
//const selectedRepeatType = ref(getLocalStorageItem('repeatType', 'Forever'), null);
// const selectedInternalDate = ref(new Date(getLocalStorageItem('selectedForDate', new Date())));
// const selectedDays = ref(
//     props.repeatText === 'Repeat Weekly' ?
//         localStorage.getItem('intervalValue') : []
// );
console.log("Final",props.frequency)
const selectedRepeatType = ref(props.repeatType ? props.repeatType : repeatType.value)
const mmStart_date = formatDateMMDDYYY(props.start_date);
const mmEnd_date = formatDateMMDDYYY(props.end_date);
const internalDate = ref(
    props.start_date ? new Date(mmStart_date) : new Date()
);
const internalForDate = ref(
    props.end_date ? new Date(mmEnd_date) : new Date()
);
const time = ref(props.timeText ? props.timeText : timeText.value)
const repeatInterval = ref( props.frequency ? props.frequency : null)
const selectedDays = ref(typeof props.frequency === 'number' ? null : props.frequency);
// const repeatInterval = ref(getLocalStorageItem('intervalValue', 1), 1);
const times = ref(getLocalStorageItem('times', 1), null);
const isTimePickerPopupVisible = ref(false);
const repeatOptions = ["Repeat Daily", "Repeat Weekly", "Repeat Monthly", "Repeat Yearly"];
const repeatType = ["Forever", "Untill", "For"];
const weekDays = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];

watch(internalDate, (newDate) => {
    const start_day = formatDate(newDate)
    localStorage.setItem('selectedInternalDate', start_day);
});

watch(internalForDate, (newDate) => {
    const end_date = formatDate(newDate)
    localStorage.setItem('selectedForDate', end_date);
});

watch(times, (newValue) => {
    localStorage.setItem('times', newValue);
});

watch(repeatInterval, (newValue) => {
    localStorage.setItem('intervalValue', newValue)
});

const getHourPosition = (hour) => {
    const angle = (hour - 3) * 30;
    const x = 48 + 38 * Math.cos(angle * Math.PI / 180);
    const y = 44 + 38 * Math.sin(angle * Math.PI / 180);
    return {
        left: `${x}%`,
        top: `${y}%`,
    };
};
const selectHour = (hour) => {
    selectedHour.value = hour;
};
const selectPeriod = (period) => {
    selectedPeriod.value = period;
};
const togglePopup = () => {
    isPopupVisible.value = !isPopupVisible.value;
};

const toggleTimePickerPopup = () => {
    isTimePickerPopupVisible.value = !isTimePickerPopupVisible.value;
};

const confirmTime = () => {
    if (selectedMinute.value > 59) {
        alert('Minute must be between 0 and 59');
    } else {
        time.value = timeText.value;
        localStorage.setItem('timeText', timeText.value);
        isTimePickerPopupVisible.value = false;
    }
};

const toggleDropdown = () => {
    isDropdownOpen.value = !isDropdownOpen.value;
};
const toggleRepeatTypeDropdown = () => {
    isDropdownRepeatTypeOpen.value = !isDropdownOpen.value;
};

const selectRepeat = (option) => {
    selectedRepeat.value = option;
    localStorage.setItem('repeatName', selectedRepeat.value);
    isDropdownOpen.value = false;
};
const selectRepeatType = (option) => {
    selectedRepeatType.value = option;
    localStorage.setItem('repeatType', selectedRepeatType.value);
    isDropdownRepeatTypeOpen.value = false;
};
const updateRepeatText = () => {
    emit('update:repeatText', {
        repeatName: selectedRepeat.value,
        intervalValue: repeatInterval.value,
        repeatType: selectedRepeatType.value,
        selectedForDate: formatDate(internalForDate.value),
        selectedInternalDate: formatDate(internalDate.value),
        times: times.value,
        timeText: timeText.value
    });
    localStorage.setItem('intervalValue', repeatInterval.value);
    localStorage.setItem('repeatType', selectedRepeatType.value);
    localStorage.setItem('repeatName', selectedRepeat.value);
    localStorage.setItem('selectedForDate', formatDate(internalForDate.value));
    localStorage.setItem('selectedInternalDate', formatDate(internalDate.value));
    localStorage.setItem('times', times.value);
    localStorage.setItem('timeText', timeText.value);
    togglePopup();
};

const toggleDay = (day) => {
    selectedDays.value = selectedDays.value.includes(day) ? [] : [day];
    repeatInterval.value = selectedDays.value[0] || null;
    localStorage.setItem('intervalValue', repeatInterval.value);
};

</script>

<style>
.v3dp__input_wrapper input:focus {
    outline: none !important;
    box-shadow: none !important;
    border-color: transparent !important;
}

button {
    border-color: #3490dc;
    transition: background-color 0.3s ease, color 0.3s ease;
}
</style>
