<template>
    <div>
        <Form :action="'Save'" @submit="submitForm">
            <InputMoney :inputValue="amount" @update:inputValue="updateAmount" />
            <Select :iconSrc="categoryIcon" :selectText="selectedCategory ? selectedCategory : 'Select category'"
                :sizeText="'16'" :getItemLabel="item => item.name" @update:selectText="updateCategory"
                @click="goPage('SelectCategories')" />
            <Note v-model="note" fromPage="AddRecurringTransaction" />
            <Recurring :repeatText="repeatName" @update:repeatText="updateRepeatTextHandler" />
            <Select :iconSrc="WalletIcon" :selectText="selectedWallet ? selectedWallet : 'Select Wallet'"
                :items="wallets" :getItemLabel="item => item.name" @click="goPage('SelectWallet')" />
            <Submit> Save</Submit>
        </Form>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import { InputMoney, Select, Note, Form, Recurring } from '@/Components/Form/Index';
import { useToast } from 'vue-toastification';
import Submit from '@/Components/Button/Submit/Index.vue';

const toast = useToast();

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

const timetext = ref(getLocalStorageItem('timetext'), null);
const intervalValue = ref(getLocalStorageItem('intervalValue'), null);
const repeatType = ref(getLocalStorageItem('repeatType'), null);
const repeatName = ref(getLocalStorageItem('repeatName', "No Repeat"));
const updateRepeatTextHandler = (value) => {
    repeatName.value = value;
};
const selectedForDate = ref(new Date(getLocalStorageItem('selectedForDate', new Date())));
const selectedInternalDate = ref(new Date(getLocalStorageItem('selectedInternalDate', new Date())));
const times = ref(getLocalStorageItem('times'), null);

const router = useRouter();
const wallets = ref([]);

const updateAmount = (value) => {
    amount.value = value;
};

const goPage = (page) => {
    router.push({
        name: page, query: {
            fromPage: 'AddRecurringTransaction'
        }
    });
};

const updateCategory = (value) => {
    selectedCategory.value = value;
    localStorage.setItem('selectedCategory', JSON.stringify(value));
};

const formatDate = (date) => {
    const day = String(date.getDate()).padStart(2, '0');
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const year = date.getFullYear();

    return `${day}/${month}/${year}`;
};

const submitForm = async () => {
    const startDate = new Date(selectedInternalDate.value);
    const formattedDate = formatDate(startDate);
    const start_date = `${formattedDate} ${timetext.value}`;
    try {
        const formData = {
            category_id: category_id.value,
            amount: amount.value,
            wallet_id: wallet_id.value,
            note: note.value,
            start_date: start_date,
            interval: repeatType.value === "Untill" ? selectedForDate.value : times.value,
            type: repeatName.value,
            frequency:  intervalValue.value,
        };
        console.log(formData);
        const response = await axios.post(route('CreateRecurringTransaction'), formData);
        if (response.data.success) {
            toast.success(response.data.message);
            window.location.href = '/transaction/recurring';
        } else {
            toast.error('Failed to create Transaction.');
        }
    } catch (error) {
        const message = error.response?.data?.message || error.message || 'An unknown error occurred';
        toast.error('Error creating Recurring Transaction: ' + message);
    }
};
</script>
