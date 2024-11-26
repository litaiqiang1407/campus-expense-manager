<template>
    <div>
        <Form :action="'Save'" @submit="submitForm">
            <InputMoney :inputValue="amount" @update:inputValue="updateAmount" />
            <Select :iconSrc="categoryIcon" :selectText="selectedCategory ? selectedCategory : 'Select category'" :sizeText="'16'"
                :getItemLabel="item => item.name" @update:selectText="updateCategory" @click="goToSelectCategories" />
            <Note v-model="note" fromPage="AddRecurringTransaction" />
            <Recurring :repeatText="repeatText" @update:repeatText="updateRepeatTextHandler" />
            <Select :iconSrc="WalletIcon" :selectText="selectedWallet ? selectedWallet : 'Select Wallet'" :items="wallets"
                :getItemLabel="item => item.name" @click="selectWallet" />
            <Submit> Save</Submit>
        </Form>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import { InputMoney, Select, Note, Form,Recurring } from '@/Components/Form/Index';
import { useToast } from 'vue-toastification';
import Submit from '@/Components/Button/Submit/Index.vue';

const toast = useToast();
const repeatText = ref("No repeat");
const updateRepeatTextHandler = (value) => {
    repeatText.value = value;  
};
const getLocalStorageItem = (key, defaultValue = null) => {
    const item = localStorage.getItem(key);
    try {
        return item ? JSON.parse(item) : defaultValue;
    } catch (error) {
        return item || defaultValue;
    }
};

const amount = ref(getLocalStorageItem('amount', '0'));
const note = ref(getLocalStorageItem('note', ''));
const selectedWallet = ref(getLocalStorageItem('selectedWallet', null));
const wallet_id = ref(getLocalStorageItem('wallet_id', null));
const selectedCategory = ref(getLocalStorageItem('selectedCategory', null));
const category_id = ref(getLocalStorageItem('categoryId', null));
const categoryIcon = ref(getLocalStorageItem('CategoryIcon', null));
const WalletIcon = ref(getLocalStorageItem('WalletIcon', null));
const transactionDate = ref(getLocalStorageItem('transactionDate') ? new Date(getLocalStorageItem('transactionDate')) : new Date());

const router = useRouter();
const wallets = ref([]);

const selectWallet = () => {
    router.push({
        name: 'SelectWallet',
        query: {
            fromPage: 'AddRecurringTransaction'
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
            fromPage: 'AddRecurringTransaction'
        }
    });
};

const updateCategory = (value) => {
    selectedCategory.value = value;
    localStorage.setItem('selectedCategory', JSON.stringify(value));
};

const submitForm = async () => {
};
</script>
