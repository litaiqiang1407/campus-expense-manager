<template>
    <div>
        <div v-if="loading" class="flex w-screen items-center justify-center h-64">
            <Loading class="size-16" />
        </div>
        <Form :action="'Save'" @submit="submitForm">
            <InputMoney :inputValue="amount.toString()" @update:inputValue="updateAmount" />
            <Select :iconSrc="categoryIcon" :selectText="selectedCategory ? selectedCategory : 'Select category'" :sizeText="'16'"
                :getItemLabel="item => item.name" @update:selectText="updateCategory" @click="goToSelectCategories" />
            <Note v-model="note" fromPage="EditTransaction" />
            <DateTimePicker v-if="!loading" :icon="'fa-regular fa-calendar'" v-model="transactionDate" />
            <Select :iconSrc="WalletIcon" :selectText="selectedWallet ? selectedWallet : 'Select Wallet'" :items="wallets"
                :getItemLabel="item => item.name" @click="selectWallet" />

            <Submit> Save</Submit>
        </Form>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import { InputMoney, Select, Note, Form, DateTimePicker } from '@/Components/Form/Index';
import { useToast } from 'vue-toastification';
import Submit from '@/Components/Button/Submit/Index.vue';
import axios from 'axios';
import Loading from '@/Components/Loading/Index.vue';

const toast = useToast();

const getLocalStorageItem = (key, defaultValue = null) => {
    const item = localStorage.getItem(key);
    try {
        return item ? JSON.parse(item) : defaultValue;
    } catch (error) {
        return item || defaultValue;
    }
};

const loading = ref(false);
const category_id = ref(getLocalStorageItem('categoryId', []));
const wallets = ref([]);
const wallet_id = ref(getLocalStorageItem('wallet_id', []));
const amount = ref(getLocalStorageItem('amount', '0'));
const note = ref(getLocalStorageItem('note', ''));
const selectedWallet = ref(getLocalStorageItem('selectedWallet', null));
const selectedCategory = ref(getLocalStorageItem('selectedCategory', null));
const transactionDate = ref(getLocalStorageItem('transactionDate') ? new Date(getLocalStorageItem('transactionDate')) : new Date());
const categoryIcon = ref(getLocalStorageItem('CategoryIcon', null));
const WalletIcon = ref(getLocalStorageItem('WalletIcon', null));

const router = useRouter();
const transactionId = router.currentRoute.value.params.transactionId;

const fetchTransactionData = async () => {
    try {
        loading.value = true;
        const response = await axios.get(route('EditTransaction', { transactionId }));
        const transactionData = response.data.transaction;

        if (!localStorage.getItem('amount')) amount.value = transactionData.amount;
        if (!localStorage.getItem('note')) note.value = transactionData.note;
        if (!localStorage.getItem('categoryId')) category_id.value = transactionData.category_id;
        if (!localStorage.getItem('wallet_id')) wallet_id.value = transactionData.wallet_id;
        if (!localStorage.getItem('selectedCategory')) selectedCategory.value = transactionData.name;
        if (!localStorage.getItem('selectedWallet')) selectedWallet.value = transactionData.wallet_name;
        if (!localStorage.getItem('transactionDate')) transactionDate.value = new Date(transactionData.date);

    } catch (error) {
        console.error('Error fetching transaction data:', error);
        toast.error('Failed to load data. Please try again.');
    } finally {
        loading.value = false;
    }
};

const selectWallet = () => {
    router.push({
        name: 'SelectWallet',
        query: {
            transactionId: transactionId,
            fromPage: 'EditTransaction'
        }
    });
};

const updateAmount = (value) => {
    amount.value = value;
};

const goToSelectCategories = () => {
    router.push({
        name: 'SelectCategories',
        query: {
            transactionId: transactionId,
            fromPage: 'EditTransaction'
        }
    });
};

const updateCategory = (value) => {
    selectedCategory.value = value;
    localStorage.setItem('selectedCategory', JSON.stringify(value));
};

const submitForm = async () => {
    try {
        const formData = {
            category_id: category_id.value,
            amount: amount.value,
            wallet_id: wallet_id.value,
            note: note.value,
            date: transactionDate.value,
            type: 'expense',
        };

        const response = await axios.post(route('UpdateTransaction', { transactionId }), formData);
        if (response.data.success) {
            toast.success(response.data.message);
            window.location.href = '/transaction';
        } else {
            toast.error('Failed to update Transaction.');
        }
    } catch (error) {
        const message = error.response?.data?.message || error.message || 'An unknown error occurred';
        toast.error('Error updating Transaction: ' + message);
    }
};

onMounted(() => {
    fetchTransactionData();
});
</script>
