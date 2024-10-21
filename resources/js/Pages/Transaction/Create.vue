<template>
    <div>
        <Form :action="'Save'" @submit="submitForm">
            <InputMoney :inputValue="amount" @update:inputValue="amount = $event" />
            <Select :selectText="selectedCategory ? selectedCategory.name : 'Select category'" :sizeText="'24'"
                :items="categories" :getItemLabel="item => item.name" @update:selectText="selectedCategory = $event" />
            <Note v-model="note" />
            <DateTimePicker :icon="'fa-regular fa-calendar'" v-model="transactionDate" />
            <RevertSelect :icon="'wallet'" :selectText="selectedWallet ? selectedWallet.name : 'Chọn ví'"
                :items="[wallets]" :getItemLabel="item => item.name" @update:selectText="selectedWallet = $event" />
            <Submit> Save</Submit>
        </Form>
    </div>
</template>

<script setup>
import { ref, onMounted , watch } from 'vue';
import { InputMoney, Select, Note, Form, DateTimePicker, RevertSelect } from '@/Components/Form/Index';
import { useToast } from 'vue-toastification';
import Submit from '@/Components/Button/Submit/Index.vue';
import { useRoute } from 'vue-router';
import axios from 'axios';

const toast = useToast();
const route = useRoute();
const walletId = ref(route.query.walletId);
const categories = ref([]);
const wallets = ref({});
const amount = ref(localStorage.getItem('amount') || '0');
const note = ref(localStorage.getItem('note') || '');
const selectedWallet = ref(JSON.parse(localStorage.getItem('selectedWallet')) || null);
const selectedCategory = ref(JSON.parse(localStorage.getItem('selectedCategory')) || null)
const transactionDate = ref(new Date());

const fetchCreateTransactionData = async () => {
    try {
        const { data } = await axios.get('/transaction/create', { params: { walletId: walletId.value } });
        categories.value = data.categories;
        wallets.value = data.wallet;
        selectedWallet.value = wallets.value;
    } catch (error) {
        toast.error('Failed to load data. Please try again.');
    }
};

const submitForm = async () => {
    try {
        const formData = {
            category_id: selectedCategory.value.id,
            amount: amount.value,
            wallet_id: selectedWallet.value.id,
            note: note.value,
            date: transactionDate.value
        };
        console.log(formData)
        const response = await axios.post('/transaction/store', formData);

        if (response.data.success) {
            toast.success(response.data.message);

            localStorage.removeItem('amount');
            localStorage.removeItem('note');
            localStorage.removeItem('selectedWallet');
            localStorage.removeItem('selectedCategory');
            localStorage.removeItem('transactionDate');

            
            window.location.href = '/transaction';
        } else {
            toast.error('Failed to create Transaction.');
        }
    } catch (error) {
        const message = error.response?.data?.message || error.message || 'An unknown error occurred';
        toast.error('Error creating Transaction: ' + message);
    }
};

watch([amount, note, selectedWallet, selectedCategory, transactionDate], () => {
    localStorage.setItem('amount', amount.value);
    localStorage.setItem('note', note.value);
    localStorage.setItem('selectedWallet', JSON.stringify(selectedWallet.value));
    localStorage.setItem('selectedCategory', JSON.stringify(selectedCategory.value));
    localStorage.setItem('transactionDate', transactionDate.value);
});

onMounted(fetchCreateTransactionData);
</script>
