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
          :items="wallets"
          :getItemLabel="item => item.name"
          @update:selectText="updateWallet"
          :destinationPage="'SelectWallet'"
        />
        <Submit>Save</Submit>
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
      categories.value = data.categories;
      wallets.value = data.wallet; // Giả định data.wallet là một mảng. Nếu không, cần điều chỉnh.

      // Thiết lập ví được chọn nếu chưa có
      if (!selectedWallet.value) {
        selectedWallet.value = wallets.value[0]; // Lấy ví đầu tiên trong danh sách
      }
    } catch (error) {
      toast.error('Failed to load data. Please try again.');
    }
  };

  // Thực thi khi component được gắn vào DOM
  onMounted(() => {
    fetchCreateTransactionData();
    if (route.query.note) {
      note.value = route.query.note;
    }
  });

  // Cập nhật giá trị
  const updateAmount = (value) => {
    amount.value = value;
  };

  const updateCategory = (value) => {
    selectedCategory.value = value;
  };

  const updateWallet = (value) => {
    selectedWallet.value = value;
  };

  // Hàm gửi biểu mẫu
  const submitForm = async () => {
    try {
      const formData = {
        category_id: selectedCategory.value ? selectedCategory.value.id : 1,
        amount: amount.value,
        wallet_id: selectedWallet.value ? selectedWallet.value.id : null,
        note: note.value,
        date: transactionDate.value,
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

  // Hàm xóa dữ liệu lưu trữ
  const clearLocalStorage = () => {
    localStorage.removeItem('amount');
    localStorage.removeItem('note');
    localStorage.removeItem('selectedWallet');
    localStorage.removeItem('selectedCategory');
    localStorage.removeItem('transactionDate');
  };

  // Theo dõi và lưu dữ liệu vào localStorage
  watch([amount, note, selectedWallet, selectedCategory, transactionDate], () => {
    saveToLocalStorage();
  });

  // Hàm lưu vào localStorage
  const saveToLocalStorage = () => {
    localStorage.setItem('amount', amount.value);
    localStorage.setItem('note', note.value);
    localStorage.setItem('selectedWallet', JSON.stringify(selectedWallet.value));
    localStorage.setItem('selectedCategory', JSON.stringify(selectedCategory.value));
    localStorage.setItem('transactionDate', transactionDate.value.toISOString());
  };
  </script>
