<template>
    <div class="relative w-full py-4">
        <button type="button" class="flex w-full space-x-2 items-center">
            <div class="size-[20px] flex items-center justify-center">
                <font-awesome-icon icon="fa-regular fa-calendar" class="text-black text-[16px]" />
            </div>
            <div class="text-secondaryText font-medium text-[14px] w-full">
                <Datepicker
                    v-model="internalDate"
                    :inputFormat="'dd-MM-yyyy'"
                    @close="isDropdownOpen = false"
                    class="datepicker"
                    :disabled="readonly"
                />
            </div>
        </button>
    </div>
</template>

<script setup>
import { ref, watch } from 'vue';
import Datepicker from 'vue3-datepicker';

const props = defineProps({
    modelValue: {
        type: [String, Date],
        default: '',
    },
    readonly: {
        type: Boolean,
        default: false,
    }
});

const emit = defineEmits(['update:modelValue']);
const internalDate = ref(props.modelValue || '');

watch(internalDate, (newDate) => {
    emit('update:modelValue', newDate);
    if (newDate) {
        localStorage.setItem('transactionDate', newDate);
    }
});
</script>

<style>
.datepicker{
    background-color: white;
}
.v3dp__datepicker {
    width: 100%;
}

.v3dp__input_wrapper input {
    width: 100%;
}

.v3dp__popout {
    width: 100% !important;
}
</style>
