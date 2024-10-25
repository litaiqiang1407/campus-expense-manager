<template>
    <div class="relative w-full">
        <!-- Button hiển thị ngày và tích hợp Datepicker -->
        <button type="button" class="flex w-full space-x-8 items-center py-4">
            <div class="size-[40px] flex items-center justify-center">
                <font-awesome-icon :icon="icon" class="text-black text-[36px]" />
            </div>
            <span class="text-secondaryText font-medium" :style="{ fontSize: sizeText + 'px' }">
                <Datepicker
                    v-model="internalDate"
                    :format="formatDateForPicker"
                    @close="isDropdownOpen = false"
                />
            </span>
        </button>
    </div>
</template>

<script setup>
import { ref, watch } from 'vue';
import Datepicker from 'vue3-datepicker';

const props = defineProps({
    icon: {
        type: String,
        default: 'calendar',
    },
    sizeText: {
        type: Number,
        default: 14,
    },
    modelValue: {
        type: String,
        default: '',
    },
});

const emit = defineEmits(['update:modelValue']);
const internalDate = ref(props.modelValue || ''); // Quản lý ngày nội bộ

// Hàm định dạng ngày
const formatDateForPicker = (date) => {
    const parsedDate = new Date(date);
    return isNaN(parsedDate.getTime())
        ? 'Select Date'
        : parsedDate.toLocaleDateString('en-US', { day: 'numeric', month: 'long', year: 'numeric' });
};

// Theo dõi sự thay đổi của internalDate và phát sự kiện cập nhật giá trị cho parent
watch(internalDate, (newDate) => {
    emit('update:modelValue', newDate);
});
</script>
