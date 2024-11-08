<template>
    <div>
        <div v-if="loading" class="flex w-screen items-center justify-center h-64">
            <Loading class="size-16" />
        </div>
        <Form :action="'Save'" @submit="submitForm">
            <InputMoney :inputValue="amount" @update:inputValue="updateAmount" />
            <Select :selectText="selectedCategory ? selectedCategory : 'Select category'" :sizeText="'16'"
                :getItemLabel="item => item.name" @update:selectText="updateCategory" @click="goToSelectCategories" />
            <Note v-model="note" />
            <DateTimePicker v-if="!loading" :icon="'fa-regular fa-calendar'" v-model="transactionDate" />
            <Select :iconSrc="null" :selectText="selectedWallet ? selectedWallet : 'Select Wallet'" :items="wallets"
                :getItemLabel="item => item.name" @click="selectWallet" />

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
import { useRouter } from 'vue-router';

<<<<<<< HEAD
  <script setup>
  import { ref, onMounted, watch } from 'vue';
  import { InputMoney, Select, Note, Form, DateTimePicker } from '@/Components/Form/Index';
  import { useToast } from 'vue-toastification';
  import Submit from '@/Components/Button/Submit/Index.vue';
  import { useRoute } from 'vue-router';
  import axios from 'axios';

  const toast = useToast();
  const route = useRoute();

  // Khởi tạo các biến trạng thái
  const walletId = ref(route.query.walletId);
  const categories = ref([]);
  const wallets = ref([]); // Sửa đổi từ đối tượng thành mảng
  const amount = ref(localStorage.getItem('amount') || '0');
  const note = ref(localStorage.getItem('note') || '');
  const selectedWallet = ref(JSON.parse(localStorage.getItem('selectedWallet')) || null);
  const selectedCategory = ref(JSON.parse(localStorage.getItem('selectedCategory')) || null);
  const transactionDate = ref(localStorage.getItem('transactionDate') ? new Date(localStorage.getItem('transactionDate')) : new Date());

  // Hàm lấy dữ liệu giao dịch
  const fetchCreateTransactionData = async () => {
    try {
      const { data } = await axios.get('/transaction/create', { params: { walletId: walletId.value } });
      console.log("data",data)
      categories.value = data.categories;
      wallets.value = data.wallet;

      if (!selectedWallet.value) {
        selectedWallet.value = wallets.value;
      }
=======
// Định nghĩa hàm getLocalStorageItem trước khi sử dụng
const getLocalStorageItem = (key, defaultValue = null) => {
    const item = localStorage.getItem(key);
    try {
        return item ? JSON.parse(item) : defaultValue;
>>>>>>> 7b27349f56529d985ad6fff254f11ddd8b5689ad
    } catch (error) {
        return item || defaultValue;
    }
};

<<<<<<< HEAD
  // Thực thi khi component được gắn vào DOM
  onMounted(() => {
=======
const toast = useToast();
const route = useRoute();
const walletId = ref(route.query.walletId);
const categories = ref([]);
const wallets = ref({});

const amount = ref(localStorage.getItem('amount'));
const note = ref(localStorage.getItem('note') || '');

const selectedWallet = ref(JSON.parse(localStorage.getItem('selectedWallet')) || null);
const selectedCategory = ref(getLocalStorageItem('selectedCategory', null));

const transactionDate = ref(localStorage.getItem('transactionDate') ? new Date(localStorage.getItem('transactionDate')) : new Date());

const router = useRoute();
const routerr = useRouter();

const goToSelectCategories = () => {
    routerr.push({
        name: 'SelectCategories',
        query: {
            fromPage: 'CreateTransaction'
        }
    });
};

const fetchCreateTransactionData = async () => {
    try {
        const { data } = await axios.get('/transaction/create', { params: { walletId: walletId.value } });
        categories.value = data.categories;
        wallets.value = data.wallet;
        if (!selectedWallet.value) {
            selectedWallet.value = wallets.value;
        }
    } catch (error) {
        toast.error('Failed to load data. Please try again.');
    }
};

onMounted(() => {
>>>>>>> 7b27349f56529d985ad6fff254f11ddd8b5689ad
    fetchCreateTransactionData();
    if (route.query.note) {
        note.value = route.query.note;
    }
});

const submitForm = async () => {
    try {
        const formData = {
            category_id: selectedCategory.value ? selectedCategory.value.id : 1,
            amount: amount.value || 1000,
            wallet_id: selectedWallet.value ? selectedWallet.value.id : 17,
            note: note.value || 'test transacion without fill',
            date: transactionDate.value || '2024-11-07T01:30:15.000Z',
        };
        const response = await axios.post('/transaction/store', formData);
        if (response.data.success) {
            toast.success(response.data.message);
            clearLocalStorage();
            window.location.href = '/transaction';
        } else {
            toast.error('Failed to create Transaction.');
        }
    } catch (error) {
        const message = error.response?.data?.message || error.message || 'An unknown error occurred';
        toast.error('Error creating Transaction: ' + message);
    }
};

const clearLocalStorage = () => {
    localStorage.removeItem('amount');
    localStorage.removeItem('note');
    localStorage.removeItem('selectedWallet');
    localStorage.removeItem('selectedCategory');
    localStorage.removeItem('transactionDate');
};
</script>
