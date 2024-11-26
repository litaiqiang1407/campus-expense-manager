<template>
    <div class="py-4 pl-0.5">
        <!-- Nút bấm để mở pop-up -->
        <button type="button" class="flex w-full space-x-2 items-center" @click="togglePopup">
            <div v-if="iconSrc" class="w-15 flex items-center justify-start">
                <img :src="iconSrc" class="w-11 h-11" />
            </div>
            <div class="flex items-center justify-center">
                <font-awesome-icon icon="fa-regular fa-calendar" class="text-black text-[16px]" />
            </div>
            <span class="font-medium text-secondaryText pl-1">{{ selectText }}</span>
        </button>

        <!-- Pop-up -->
        <div v-if="isPopupVisible" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg shadow-lg w-auto p-4 m-4">
                <!-- Dropdown để chọn Repeat -->
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
                        <div class="time-picker relative cursor-pointer z-20 flex items-center -ml-14 space-x-2">
                            <h1 class=" relative cursor-pointer z-20 -ml-5">at:</h1>
                            <!-- Time Picker Section -->
                            <div @click="toggleTimePickerPopup">
                                <span >{{ timeText || "Select Time" }}</span>
                            </div>
                        </div>
                        <!-- Pop-up chọn thời gian -->
                        <div v-if="isTimePickerPopupVisible"
                            class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                            <div class="bg-white p-5 rounded-lg shadow-lg w-72 m-5">
                                <h2 class="font-bold mb-4 text-center text-lg">Choose Time</h2>

                                <!-- Đồng hồ hình tròn -->
                                <div
                                    class="relative w-full h-60 rounded-full border-2 border-gray-300 mx-auto mb-6 flex items-center justify-center">
                                    <!-- Các mốc giờ -->
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

                                <!-- Input giờ và phút -->
                                <div class="flex mb-4">
                                    <!-- Input giờ -->
                                    <div class="w-1/3 mr-2">
                                        <label for="hour" class="block text-sm font-medium text-gray-700">Hour</label>
                                        <input v-model="selectedHour" type="number" min="1" max="12"
                                            class="w-full text-center text-xl p-2.5 border border-gray-300 " readonly />
                                    </div>

                                    <!-- Input phút -->
                                    <div class="w-1/3 mr-2">
                                        <label for="minute"
                                            class="block text-sm font-medium text-gray-700">Minute</label>
                                        <input v-model="selectedMinute" type="number" min="0" max="59"
                                            class="w-full text-center text-xl p-2.5 border border-gray-300 " />
                                    </div>
                                    <div class="w-1/3">
                                        <label class="block text-sm font-medium text-gray-700">AM/PM</label>
                                        <!-- Sử dụng flex-col để xếp AM/PM theo chiều dọc -->
                                        <div class="flex flex-col space-y-1">
                                            <button @click="selectPeriod('AM')" :class="{
                                                'bg-primary text-white': selectedPeriod === 'AM',
                                                'bg-white text-primary': selectedPeriod !== 'AM'
                                            }" class="w-full text-[14px] border">
                                                AM
                                            </button>
                                            <button @click="selectPeriod('PM')" :class="{
                                                'bg-primary text-white': selectedPeriod === 'PM',
                                                'bg-white text-primary': selectedPeriod !== 'PM'
                                            }" class="w-full text-[14px] border">
                                                PM
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <!-- Nút Done -->
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
                <!-- Chọn "Repeat Weekly" -->
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

                <!-- Các form khác (Monthly, Yearly) -->
                <div v-if="selectedRepeat === 'Repeat Monthly'">
                    <!-- Form Repeat Monthly -->
                    <h1>Repeat Monthly Form</h1>
                </div>
                <div v-if="selectedRepeat === 'Repeat Yearly'">
                    <!-- Form Repeat Yearly -->
                    <h1>Repeat Yearly Form</h1>
                </div>

                <!-- Button group -->
                <div class="flex justify-end mt-4">
                    <button type="button" class="bg-white text-primary font-medium py-2 px-4 rounded-md"
                        @click="togglePopup">
                        CANCEL
                    </button>
                    <button type="button" class="bg-primary text-white font-medium py-2 px-4 rounded-md"
                        @click="saveChanges">
                        DONE
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed } from "vue";
import Datepicker from "vue3-datepicker";
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';

const props = defineProps({
    iconSrc: { type: String, default: null },
    selectText: { type: String, default: "No repeat" },
});

// Reactive properties
const isPopupVisible = ref(false);
const isDropdownOpen = ref(false);
const selectedRepeat = ref("Repeat Daily");
const internalDate = ref(new Date());
const repeatInterval = ref(1);
const isTimePickerPopupVisible = ref(false);

const selectedHour = ref(12);
const selectedMinute = ref(0);
const selectedPeriod = ref("AM");

const vTime = computed(() => {
    return `${selectedHour.value}:${selectedMinute.value < 10 ? '0' : ''}${selectedMinute.value} ${selectedPeriod.value}`;
});

const repeatOptions = ["Repeat Daily", "Repeat Weekly", "Repeat Monthly", "Repeat Yearly"];
const weekDays = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
const selectedDays = ref([]); // Store selected days for Repeat Weekly

const timeText = computed(() => {
    const hour = selectedHour.value;
    const minute = selectedMinute.value < 10 ? '0' + selectedMinute.value : selectedMinute.value;
    return `${hour}:${minute} ${selectedPeriod.value}`;
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

// Toggle popup visibility
const togglePopup = () => {
    isPopupVisible.value = !isPopupVisible.value;
};

const toggleTimePickerPopup = () => {
    isTimePickerPopupVisible.value = !isTimePickerPopupVisible.value;
};

const confirmTime = () => {
    isTimePickerPopupVisible.value = false;
};

// Toggle dropdown for repeat options
const toggleDropdown = () => {
    isDropdownOpen.value = !isDropdownOpen.value;
};

// Select a repeat option
const selectRepeat = (option) => {
    selectedRepeat.value = option;
    isDropdownOpen.value = false;
};

// Save changes and close popup
const saveChanges = () => {
    console.log(`Selected Date: ${internalDate.value}`);
    console.log(`Selected Time: ${vTime.value}`);
    console.log(`Repeat Option: ${selectedRepeat.value}`);
    isPopupVisible.value = false;
};

// Toggle day selection for Repeat Weekly
const toggleDay = (day) => {
    if (selectedDays.value.includes(day)) {
        selectedDays.value = selectedDays.value.filter(d => d !== day); // Remove day from selected days
    } else {
        selectedDays.value.push(day); // Add day to selected days
    }
};
</script>

<style scoped>
button {
    border-color: #3490dc;
    transition: background-color 0.3s ease, color 0.3s ease;
}
</style>
