<template>
    <div>
        <Form :action="'Save'" @submit="submitForm">
            <InputMoney :inputValue="amount" @update:inputValue="updateAmount" />
            <Select
                :selectText="selectedCategory ? selectedCategory.name : 'Select category'"
                :sizeText="'16'"
                :items="categories"
                :getItemLabel="item => item.name"
                @update:selectText="updateCategory"
            />
            <Note v-model="note" />
            <DateTimePicker :icon="'fa-regular fa-calendar'" v-model="transactionDate" />
            <Select
                :icon="'wallet'"
                :selectText="selectedWallet ? selectedWallet.name : 'Select Wallet'"
                :items="[wallets]"
                :getItemLabel="item => item.name"
                @update:selectText="updateWallet"
                :destinationPage="'SelectWallet'"
            />
            <Submit> Save</Submit>
        </Form>
    </div>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue';
import { InputMoney, Select, Note, Form, DateTimePicker } from '@/Components/Form/Index';
import { useToast } from 'vue-toastification';
import Submit from '@/Components/Button/Submit/Index.vue';
import { useRoute } from 'vue-router';
import axios from 'axios';

const toast = useToast();
const route = useRoute();

const transactionId = ref(route.query.transactionId); // ID của giao dịch cần chỉnh sửa
const walletId = ref(route.query.walletId);
const categories = ref([]);
const wallets = ref({});
const amount = ref(localStorage.getItem('amount') || '0');
const note = ref(localStorage.getItem('note') || '');
// const selectedWallet = ref(JSON.parse(localStorage.getItem('selectedWallet')) || null);
// const selectedCategory = ref(JSON.parse(localStorage.getItem('selectedCategory')) || null);
// const transactionDate = ref(localStorage.getItem('transactionDate') ? new Date(localStorage.getItem('transactionDate')) : new Date());
const props = defineProps({
    transactionId: {
    type: String,
    required: true,
  },
});
const fetchTransactionData = async () => {
    try {
        const response = await axios.get(`/transaction/edit/${transactionId}`); // Điều chỉnh endpoint cho phù hợp

        // Log dữ liệu để kiểm tra
        console.log("Fetched Data:", response.data); // Chỉ log dữ liệu data

        // Gán giá trị từ response.data
        // categories.value = response.data.categories; // Sử dụng response.data
        // wallets.value = response.data.wallets;       // Sử dụng response.data
        // amount.value = response.data.transaction.amount; // Sử dụng response.data
        // note.value = response.data.transaction.note; // Sử dụng response.data
        // transactionDate.value = new Date(response.data.transaction.date); // Sử dụng response.data
        // selectedWallet.value = response.data.transaction.wallet_id; // Sử dụng response.data
        // selectedCategory.value = response.data.transaction.category_id; // Sử dụng response.data
    } catch (error) {
        console.error('Error fetching transaction data:', error); // Log lỗi nếu có
        toast.error('Failed to load data. Please try again.');
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

// Lưu trạng thái vào localStorage khi có thay đổi
// watch([amount, note, selectedWallet, selectedCategory, transactionDate], () => {
//     saveToLocalStorage();
// });

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
