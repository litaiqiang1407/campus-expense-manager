<template>
    <div class="relative">
        <button type="button" class="flex w-full space-x-8 items-center py-4" @click="toggleDropdown">
            <div class="size-[40px] flex items-center justify-center">
                <font-awesome-icon :icon="icon" class="text-black text-[36px]" />
            </div>
            <span class="text-secondaryText font-medium" :style="{ fontSize: sizeText + 'px' }">{{ formattedDate
                }}</span>
        </button>

        <!-- Dropdown List -->
        <div v-if="isDropdownOpen" class="absolute z-10 w-full bg-white border border-gray-300 rounded-md mt-2">
            <input type="date" @change="onDateChange" class="border border-gray-300 rounded-md p-2 w-full" />
        </div>
    </div>
</template>

<script setup>
import { ref, computed } from 'vue';

const props = defineProps({
    icon: {
        type: String,
        default: 'calendar', // Default icon name
    },
    sizeText: {
        type: Number,
        default: 14,
    },
    modelValue: {
        type: String,
        default: '', // Default value for the selected date
    },
});

const emit = defineEmits(['update:modelValue']);

const isDropdownOpen = ref(false);

const formattedDate = computed(() => {
    // Check if modelValue is an empty proxy array or null, undefined, etc.
    if (!props.modelValue || (Array.isArray(props.modelValue) && props.modelValue.length === 0)) {
        // If modelValue is null, undefined, or an empty array, use today's date
        const date = new Date();
        return formatDate(date); // Call the formatDate function to handle formatting
    } else {
        // If modelValue exists, attempt to format it
        const date = new Date(props.modelValue);
        return isNaN(date.getTime()) ? 'Invalid Date' : formatDate(date); // Handle invalid date format
    }
});

// Utility function to format the date as needed
const formatDate = (date) => {
    const today = new Date();

    // Check if the date is today
    const isToday = today.toDateString() === date.toDateString();

    // Check if the date is yesterday
    const yesterday = new Date(today);
    yesterday.setDate(today.getDate() - 1);
    const isYesterday = yesterday.toDateString() === date.toDateString();

    // Calculate if it's within this week
    const daysDiff = Math.floor((today - date) / (1000 * 3600 * 24)); // Days difference
    const isThisWeek = daysDiff <= 7 && today.getDay() >= date.getDay();

    if (isToday) {
        return "Today";
    } else if (isYesterday) {
        return "Yesterday";
    } else if (isThisWeek) {
        return date.toLocaleDateString('en-US', { weekday: 'long' }); // For example: Monday
    } else {
        return date.toLocaleDateString('en-US', { day: 'numeric', month: 'long', year: 'numeric' }); // Full date
    }
};

const toggleDropdown = () => {
    isDropdownOpen.value = !isDropdownOpen.value;
};

const onDateChange = (event) => {
    const selectedDate = event.target.value;
    emit('update:modelValue', selectedDate);
    isDropdownOpen.value = false;
};
</script>
