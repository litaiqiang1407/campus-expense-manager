<template>
    <div>
        <div v-if="loading" class="flex w-screen items-center justify-center h-64">
            <Loading class="size-16" />
        </div>
        <Form :action="'Save'" @submit="submitForm">
            <InputMoney :inputValue="amount" @update:inputValue="updateAmount" />
            <Select :selectText="selectedCategory ? selectedCategory : 'Select category'" :sizeText="'16'"
                :getItemLabel="item => item.name" @update:selectText="updateCategory" @click="goToSelectCategories" />
            <Note v-model="note" fromPage="CreateTransaction" />
            <DateTimePicker v-if="!loading" :icon="'fa-regular fa-calendar'" v-model="transactionDate" />
            <Select :iconSrc="null" :selectText="selectedWallet ? selectedWallet : 'Select Wallet'" :items="wallets"
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
const amount = ref(getLocalStorageItem('amount', '0'));
const note = ref(getLocalStorageItem('note', ''));
const selectedWallet = ref(getLocalStorageItem('selectedWallet', null));
const wallet_id = ref(getLocalStorageItem('wallet_id', null));
const selectedCategory = ref(getLocalStorageItem('selectedCategory', null));
const category_id = ref(getLocalStorageItem('categoryId', null));
const transactionDate = ref(getLocalStorageItem('transactionDate') ? new Date(getLocalStorageItem('transactionDate')) : new Date());

const router = useRouter();
const categories = ref([]);
const wallets = ref([]);

const fetchCreateTransactionData = async () => {
    try {
        loading.value = true;
        const { data } = await axios.get('/transaction/create');
        categories.value = data.categories;
    } catch (error) {
        toast.error('Failed to load data. Please try again.');
    } finally {
        loading.value = false;
    }
};

const selectWallet = () => {
    router.push({
        name: 'SelectWallet',
        query: {
            fromPage: 'CreateTransaction'
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
            fromPage: 'CreateTransaction'
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
        };
        const response = await axios.post('/transaction/store', formData);
        if (response.data.success) {
            toast.success(response.data.message);
            window.location.href = '/transaction';
        } else {
            toast.error('Failed to create Transaction.');
        }
    } catch (error) {
        const message = error.response?.data?.message || error.message || 'An unknown error occurred';
        toast.error('Error creating Transaction: ' + message);
    }
};

onMounted(() => {
    fetchCreateTransactionData();
});
</script>
