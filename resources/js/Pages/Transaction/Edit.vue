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
            <Select :icon="'wallet'" :selectText="selectedWallet ? selectedWallet.name : 'Select Wallet'"
                :items="wallets" :getItemLabel="item => item.name" @update:selectText="updateWallet"
                :destinationPage="'SelectWallet'" />
            <Submit> Save</Submit>
        </Form>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { InputMoney, Select, Note, Form, DateTimePicker } from '@/Components/Form/Index';
import { useToast } from 'vue-toastification';
import Submit from '@/Components/Button/Submit/Index.vue';
import axios from 'axios';
import Loading from '@/Components/Loading/Index.vue';

const toast = useToast();
const loading = ref(false);

const categories = ref([]);
const wallets = ref([]); // Sửa thành mảng
const amount = ref(localStorage.getItem('amount') || '0');
const note = ref(localStorage.getItem('note') || '');
const selectedCategory = ref(null); // Thêm khai báo cho selectedCategory
const selectedWallet = ref(null);
const transactionDate = ref(["2024-10-31"]);

const props = defineProps({
    transactionId: {
        type: String,
        required: true,
    },
});
const fetchTransactionData = async () => {
    try {
        loading.value = true;
        const response = await axios.get(route('EditTransaction', { transactionId: props.transactionId || '147' }));
        console.log("data", response.data); // Kiểm tra toàn bộ dữ liệu phản hồi
        const transactionData = response.data.transaction; // Dữ liệu phản hồi
        console.log("data2",transactionData.date)
        // Cập nhật các biến với dữ liệu từ transactionData
        amount.value = transactionData.amount; // Lấy amount
        note.value = transactionData.note; // Lấy note
        selectedCategory.value = transactionData.category || { id: 1, name: 'Default Category' }; // Cập nhật nếu có dữ liệu
        selectedWallet.value = transactionData.wallet || { id: 1, name: 'Default Wallet' }; // Cập nhật nếu có dữ liệu
        // transactionDate.value = "2024-10-31"; // Sử dụng hàm parseDate để chuyển đổi

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
            wallet_id: selectedWallet.value ? selectedWallet.value.id : null,
            note: note.value,
            date: transactionDate.value,
        };

        const response = await axios.put(`/transaction/${transactionId.value}/update`, formData);

        if (response.data.success) {
            toast.success(response.data.message);
            saveToLocalStorage(); // Gọi hàm saveToLocalStorage sau khi cập nhật thành công
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
