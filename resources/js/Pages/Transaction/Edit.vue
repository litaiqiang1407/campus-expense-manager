<template>
    <div>
        <div v-if="loading" class="flex w-screen items-center justify-center h-64">
            <Loading class="size-16" />
        </div>
        <Form :action="'Save'" @submit="submitForm">
            <InputMoney :inputValue="amount" @update:inputValue="updateAmount" />
            <Select :selectText="selectedCategory ? selectedCategory.name : 'Select category'" :sizeText="'16'"
                :items="categories" :getItemLabel="item => item.name" @update:selectText="updateCategory" />
            <Note v-model="note" />
            <DateTimePicker :icon="'fa-regular fa-calendar'" v-model="transactionDate" />
            <Select :icon="'wallet'" :selectText="selectedWallet ? selectedWallet : 'Select Wallet'" :items="wallets"
                :getItemLabel="item => item.name" @update:selectText="updateWallet" :destinationPage="'SelectWallet'" />
            <Submit> Save</Submit>
        </Form>
    </div>
</template>
<script setup>
import { ref, onMounted } from 'vue';
import { useRoute } from 'vue-router'; // Import useRoute
import { InputMoney, Select, Note, Form, DateTimePicker } from '@/Components/Form/Index';
import { useToast } from 'vue-toastification';
import Submit from '@/Components/Button/Submit/Index.vue';
import axios from 'axios';
import Loading from '@/Components/Loading/Index.vue';
import { fromJSON } from 'postcss';

const toast = useToast();
const loading = ref(false);

const categories = ref([]);
const wallets = ref([]);
const amount = ref(localStorage.getItem('amount') || '0');
const note = ref(localStorage.getItem('note') || '');
const selectedWallet = ref(JSON.parse(localStorage.getItem('selectedWallet')) || null);
const selectedCategory = ref(JSON.parse(localStorage.getItem('selectedCategory')) || null);
const transactionDate = ref(localStorage.getItem('transactionDate') ? new Date(localStorage.getItem('transactionDate')) : new Date());

// Lấy transactionId từ route params
const router = useRoute();
const transactionId = router.params.transactionId; // Sử dụng params từ route

console.log("transactionId from params:", transactionId);

const fetchTransactionData = async () => {
    try {
        loading.value = true;
        const response = await axios.get(route('EditTransaction', { transactionId: transactionId}));
        console.log("data", response.data); // Kiểm tra toàn bộ dữ liệu phản hồi
        const transactionData = response.data.transaction; // Dữ liệu phản hồi
        categories.value = response.data.categories;
        amount.value = transactionData.amount;
        note.value = transactionData.note;
        selectedCategory.value = transactionData.category || { id: 1, name: 'Default Category' };
        selectedWallet.value = transactionData.wallet_name;
        transactionDate.value = transactionData.date;
    } catch (error) {
        console.error('Error fetching transaction data:', error);
        toast.error('Failed to load data. Please try again.');
    } finally {
        loading.value = false;
    }
};
const updateAmount = (value) => {
    amount.value = value;
};

const updateCategory = (value) => {
    selectedCategory.value = value;
};

const updateWallet = (value) => {
    selectedWallet.value = value;
};

// Gửi dữ liệu chỉnh sửa
const submitForm = async () => {
    try {
        const formData = {
            category_id: selectedCategory.value ? selectedCategory.value.id : 1,
            amount: amount.value,
            wallet_id: 11,
            note: note.value,
            date: transactionDate.value,
            type: 'expense', // hoặc 'income', tùy theo loại giao dịch
        };
        console.log("data", formData);

        const response = await axios.post(route('UpdateTransaction', { transactionId: transactionId }), formData);
        if (response.data.success) {
            toast.success(response.data.message);
            saveToLocalStorage();
            clearLocalStorage();
            window.location.href = '/transaction';
        } else {
            toast.error('Failed to update Transaction.');
        }
    } catch (error) {
        const message = error.response?.data?.message || error.message || 'An unknown error occurred';
        toast.error('Error updating Transaction: ' + message);
    }
};

const clearLocalStorage = () => {
    localStorage.removeItem('amount');
    localStorage.removeItem('note');
    localStorage.removeItem('selectedWallet');
    localStorage.removeItem('selectedCategory');
    localStorage.removeItem('transactionDate');
};

const saveToLocalStorage = () => {
    localStorage.setItem('amount', amount.value);
    localStorage.setItem('note', note.value);
    localStorage.setItem('selectedWallet', JSON.stringify(selectedWallet.value));
    localStorage.setItem('selectedCategory', JSON.stringify(selectedCategory.value));
    localStorage.setItem('transactionDate', transactionDate.value.toISOString());
};

onMounted(() => {
    fetchTransactionData();
});
</script>
