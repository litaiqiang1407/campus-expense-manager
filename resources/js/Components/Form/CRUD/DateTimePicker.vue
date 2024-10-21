<template>
    <div class="relative">
        <button type="button" class="flex w-full space-x-8 items-center py-4" @click="toggleDropdown">
            <div class="size-[40px] flex items-center justify-center">
                <font-awesome-icon :icon="icon" class="text-black text-[36px]" />
            </div>
            <span class="text-secondaryText font-medium" :style="{ fontSize: sizeText + 'px' }">{{ formattedDate }}</span>
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
    if (!props.modelValue || (Array.isArray(props.modelValue) && props.modelValue.length === 0)) {
        const date = new Date();
        return formatDate(date);
    } else {
        const date = new Date(props.modelValue);
        return isNaN(date.getTime()) ? 'Invalid Date' : formatDate(date);
    }
});

// Utility function to format the date as needed
const formatDate = (date) => {
    const today = new Date();
    const isToday = today.toDateString() === date.toDateString();
    const yesterday = new Date(today);
    yesterday.setDate(today.getDate() - 1);
    const isYesterday = yesterday.toDateString() === date.toDateString();
    const daysDiff = Math.floor((today - date) / (1000 * 3600 * 24));
    const isThisWeek = daysDiff <= 7 && today.getDay() >= date.getDay();

    if (isToday) {
        return "Today";
    } else if (isYesterday) {
        return "Yesterday";
    } else if (isThisWeek) {
        return date.toLocaleDateString('en-US', { weekday: 'long' });
    } else {
        return date.toLocaleDateString('en-US', { day: 'numeric', month: 'long', year: 'numeric' });
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
