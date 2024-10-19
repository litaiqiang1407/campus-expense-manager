<template>
    <div>
        <Form :action="'Save'" @submit="submitForm">
            <InputMoney :inputValue="amount" @update:inputValue="amount = $event" />
            <Select :selectText="selectedCategory ? selectedCategory.name : 'Select category'" :sizeText="'24'"
                :items="categories" :getItemLabel="item => item.name" @update:selectText="selectedCategory = $event" />
            <Note v-model="note" />
            <!-- Date Picker Input -->
            <DateTimePicker :icons="'fa-calendar'" />
            <!-- Chỗ này hiển thị tên ví được chọn hoặc 'Chọn ví' -->
            <RevertSelect :icon="'wallet'" :selectText="selectedWallet ? selectedWallet.name : 'Chọn ví'"
                :items="[wallets]" :getItemLabel="item => item.name" @update:selectText="selectedWallet = $event" />
            <Submit> Save</Submit>
        </Form>
    </div>
</template>
<script setup>
import { ref, onMounted } from 'vue';
import { InputMoney, Select, Note, Form, DateTimePicker, RevertSelect } from '@/Components/Form/Index';
import { useToast } from 'vue-toastification';
import Submit from '@/Components/Button/Submit/Index.vue';
import { useRoute } from 'vue-router'; // Import useRoute để lấy query params
import axios from 'axios';

const toast = useToast();
const route = useRoute(); // Sử dụng useRoute để truy xuất query params
const walletId = ref(route.query.walletId); // Lấy walletId từ query params và lưu vào ref
const categories = ref([]);
const wallets = ref({}); // Lưu thông tin của ví
const amount = ref('0');
const note = ref('');
const date = ref(new Date());
const selectedWallet = ref(null); // Thêm selectedWallet để lưu ví đã chọn
const selectedCategory = ref(null);

// Fetch dữ liệu khi component mounted
const fetchCreateTransactionData = async () => {
    try {
        const response = await axios.get('/transaction/create', {
            params: {
                walletId: walletId.value,
            },
        });

        categories.value = response.data.categories; 
        wallets.value = response.data.wallet;
        selectedWallet.value = wallets.value;
    } catch (error) {
        console.error('Error fetching create transaction data:', error);
        toast.error('Failed to load data. Please try again.');
    }
};

const submitForm = async () => {
    try {
        const formData = {
            category_id: 2,
            amount: 10,
            wallet_id: 4,
            note: 'hieu',
            date: '2024-10-04'
        };
        const response = await axios.post(route('StoreTransaction'), formData);

        if (response.data.success) {
            toast.success(response.data.message);
            window.location.href = route('Transaction');
        } else {
            toast.error('Failed to create Transaction.');
        }
    } catch (error) {
        toast.error('Error creating Transaction: ' + error.response?.data?.message || error.message);
    }
};

onMounted(fetchCreateTransactionData);
</script>
