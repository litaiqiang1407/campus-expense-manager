<template>
    <div class="relative">
        <button
            type="button"
            class="flex w-full space-x-8 items-center py-4"
            @click="toggleDropdown"
        >
            <div class="size-[40px] flex items-center justify-center">
                <font-awesome-icon :icon="icon" class="text-black text-[36px]" />
            </div>
            <span class="text-secondaryText font-medium" :style="{ fontSize: sizeText + 'px' }">{{ formattedDate }}</span>
        </button>

        <!-- Dropdown List -->
        <div
            v-if="isDropdownOpen"
            class="absolute z-10 w-full bg-white border border-gray-300 rounded-md mt-2"
        >
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
const formattedDate = computed(() => (props.modelValue ? new Date(props.modelValue).toLocaleDateString() : 'To day'));

const toggleDropdown = () => {
    isDropdownOpen.value = !isDropdownOpen.value;
};

const onDateChange = (event) => {
    const selectedDate = event.target.value;
    emit('update:modelValue', selectedDate); 
    isDropdownOpen.value = false;
};
</script>
