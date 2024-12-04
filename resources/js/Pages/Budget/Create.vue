<template>
    <Form :action="'Save'" @submit="submitForm">
        <!-- Category Selection -->
        <Select :iconSrc="categoryIcon" :selectText="selectedCategory || 'Select category'"
                :sizeText="'16'" :getItemLabel="item => item.name" @click="goPage('SelectCategories')" />

        <!-- Input Money -->
        <InputMoney :inputValue="amount" @update:inputValue="amount = $event" />

        <!-- Date Selection -->
        <div class="relative">
            <Select :icon="'fa-regular fa-calendar'" :selectText="formattedDateOption || 'Today'" @click="toggleDateOptions" />

            <!-- Date Options Popup -->
            <div v-if="showDateOptions" class="fixed inset-0 bg-black bg-opacity-50 flex items-end justify-center z-50" @click="cancelDateSelection"></div>
            <div v-if="showDateOptions" class="fixed inset-x-0 bottom-0 z-50 w-full bg-white border-t border-gray-300 rounded-t-lg shadow-lg transform transition-transform duration-300 ease-out" :class="{ 'translate-y-full': !showDateOptions, 'translate-y-0': showDateOptions }">
                <div class="p-4">
                    <h3 class="text-2xl font-bold tracking-wide">Select time range</h3>
                    <ul class="mt-2">
                        <li v-for="option in dateOptions" :key="option.value" @click="selectDateOption(option.value)" class="flex items-center py-2 cursor-pointer text-lg">
                            <input type="radio" :value="option.value" v-model="selectedDateOption" class="mr-2 h-5 w-5" />
                            <span class="text-lg font-medium tracking-normal">{{ option.label }}</span>
                        </li>
                    </ul>

                    <!-- Custom Date Range Input -->
                    <div v-if="isCustomDateRange" class="mt-4 flex items-center justify-center">
                        <div class="flex items-center">
                            <span class="mr-2 text-lg font-medium tracking-normal">From</span>
                            <input type="date" v-model="customFrom" class="border-gray-300 rounded-md shadow-sm text-base w-32" />
                        </div>
                        <span class="mx-2 text-base font-medium tracking-normal">-</span>
                        <div class="flex items-center">
                            <span class="mr-2 text-base font-medium tracking-normal">To</span>
                            <input type="date" v-model="customTo" class="border-gray-300 rounded-md shadow-sm text-base w-32" />
                        </div>
                    </div>
                </div>

                <!-- Save & Cancel Buttons -->
                <div class="flex justify-center p-4 border-t border-gray-200 space-x-4">
                    <button @click="cancelDateSelection" class="flex-1 px-6 py-3 text-gray-500 bg-primaryBackground border border-gray-300 rounded-full text-lg font-medium">Cancel</button>
                    <button @click="applyDateRange" class="flex-1 px-6 py-3 text-white bg-primary rounded-full text-lg font-medium">Save</button>
                </div>
            </div>
        </div>

        <!-- Wallet Selection -->
        <Select :iconSrc="WalletIcon" :selectText="selectedWallet || 'Select Wallet'" :getItemLabel="item => item.name" @click="goPage('SelectWallet')" />

        <!-- Submit Button -->
        <Submit>Create Budget</Submit>
    </Form>
</template>

<script setup>
import { ref, computed } from 'vue';
import { InputMoney, Select, Form } from '@/Components/Form/Index';
import Submit from '@/Components/Button/Submit/Index.vue';
import { useToast } from 'vue-toastification';
import axios from 'axios';
import { useRouter } from 'vue-router';

const toast = useToast();
const router = useRouter();

// State variables
const amount = ref('0');
const categoryIcon = ref(localStorage.getItem('CategoryIcon'));
const selectedCategory = ref(localStorage.getItem('selectedCategory'));
const selectedCategoryId = ref(localStorage.getItem('categoryId'));
const showDateOptions = ref(false);
const selectedDateOption = ref('');
const customFrom = ref('');
const customTo = ref('');
const wallet_id = ref(localStorage.getItem('wallet_id'));
const selectedWallet = ref(localStorage.getItem('selectedWallet'));
const WalletIcon = ref(localStorage.getItem('WalletIcon'));

// Format Date Utilities
const formatDate = (date) => date.toLocaleDateString('en-GB', { day: '2-digit', month: '2-digit' });
const formatDateYear = (date) => date.toLocaleDateString('en-GB');

// Today's Date
const today = new Date();
const dayOfWeek = today.getDay();
const startOfWeek = new Date(today);
const endOfWeek = new Date(today);

startOfWeek.setDate(today.getDate() - (dayOfWeek === 0 ? 6 : dayOfWeek - 1));
endOfWeek.setDate(startOfWeek.getDate() + 6);

const startOfMonth = new Date(today.getFullYear(), today.getMonth(), 1);
const endOfMonth = new Date(today.getFullYear(), today.getMonth() + 1, 0);

const startOfQuarter = new Date(today.getFullYear(), Math.floor(today.getMonth() / 3) * 3, 1);
const endOfQuarter = new Date(startOfQuarter.getFullYear(), startOfQuarter.getMonth() + 3, 0);

const startOfYear = new Date(today.getFullYear(), 0, 1);
const endOfYear = new Date(today.getFullYear(), 11, 31);

// Date Options
const dateOptions = computed(() => [
    { value: 'week', label: `This week (${formatDate(startOfWeek)} - ${formatDate(endOfWeek)})` },
    { value: 'month', label: `This month (${formatDate(startOfMonth)} - ${formatDate(endOfMonth)})` },
    { value: 'quarter', label: `This quarter (${formatDate(startOfQuarter)} - ${formatDate(endOfQuarter)})` },
    { value: 'year', label: `This year (01/01/${today.getFullYear()} - 31/12/${today.getFullYear()})` },
    { value: 'Custom', label: 'Custom' }
]);

// Computed Properties
const isCustomDateRange = computed(() => selectedDateOption.value === 'Custom');
const formattedDateOption = computed(() => {
    if (isCustomDateRange.value && customFrom.value && customTo.value) {
        return `${formatDateYear(new Date(customFrom.value))} - ${formatDateYear(new Date(customTo.value))}`;
    }
    const selectedOption = dateOptions.value.find(option => option.value === selectedDateOption.value);
    return selectedOption ? selectedOption.label : 'Select time range';
});

// Date Range Utility
const getStartAndEndDay = (option) => {
    switch (option) {
        case 'week': return { start: formatDateForServer(startOfWeek), end: formatDateForServer(endOfWeek) };
        case 'month': return { start: formatDateForServer(startOfMonth), end: formatDateForServer(endOfMonth) };
        case 'quarter': return { start: formatDateForServer(startOfQuarter), end: formatDateForServer(endOfQuarter) };
        case 'year': return { start: formatDateForServer(startOfYear), end: formatDateForServer(endOfYear) };
        case 'Custom': return { start: formatDateForServer(customFrom.value), end: formatDateForServer(customTo.value) };
        default: return { start: null, end: null };
    }
};

// Methods
const toggleDateOptions = () => showDateOptions.value = !showDateOptions.value;
const selectDateOption = (option) => selectedDateOption.value = option;
const cancelDateSelection = () => {
    showDateOptions.value = false;
    customFrom.value = '';
    customTo.value = '';
    selectedDateOption.value = '';
};
const applyDateRange = () => {
    if (isCustomDateRange.value && customFrom.value && customTo.value) {
        const fromDate = new Date(customFrom.value);
        const toDate = new Date(customTo.value);
        if (fromDate > toDate) {
            toast.error('Ending date cannot be earlier than the starting date');
            return;
        }
    }
    showDateOptions.value = false;
};

const formatDateForServer = (date) => {
    if (!date) return null;
    const d = new Date(date);
    const day = String(d.getDate()).padStart(2, '0');
    const month = String(d.getMonth() + 1).padStart(2, '0');
    const year = d.getFullYear();
    return `${day}/${month}/${year}`;
};

// Form Submission
const submitForm = async () => {
    const formData = {
        amount: amount.value.trim(),
        wallet_id: wallet_id.value,
        category_id: Number(selectedCategoryId.value),
        start_date: getStartAndEndDay(selectedDateOption.value).start,
        end_date: getStartAndEndDay(selectedDateOption.value).end,
    };
    try {
        const response = await axios.post('/budget/store', formData);
        if (response.data.success) {
            toast.success(response.data.message);
            const redirectRoute = response.data.userHasWallet ? 'Home' : 'Budget';
            router.push({ name: redirectRoute });
        } else {
            toast.error('Failed to create budget.');
        }
    } catch (error) {
        toast.error('Error creating budget: ' + (error.response?.data?.message || error.message));
    }
};

// Navigation
const goPage = (page) => {
    router.push({ name: page, query: { fromPage: 'CreateBudget' } });
};
</script>
