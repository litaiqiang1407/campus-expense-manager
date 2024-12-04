<template>
    <div>
        <Form :action="'Save'" @submit="submitForm">
            <InputMoney :inputValue="amount.toString()" @update:inputValue="updateAmount" />
            <Select :iconSrc="categoryIcon" :selectText="selectedCategory ? selectedCategory : 'Select category'"
                :sizeText="'16'" :getItemLabel="item => item.name" @update:selectText="updateCategory"
                @click="goPage('SelectCategories')" />
            <Note v-model="note" fromPage="AddRecurringTransaction" />
            <Recurring :repeatText="repeatName" :start_date="selectedInternalDate" :timeText="timetext" :times="times" :repeatType="repeatType"
                :end_date="selectedForDate" :frequency="intervalValue" @update:repeatText="updateRepeatTextHandler" />
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
const formatDate = (date) => {
    const day = String(date.getDate()).padStart(2, '0');
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const year = date.getFullYear();

    return `${day}/${month}/${year}`;
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

const repeatName = ref(getLocalStorageItem('repeatName', "Repeat Daily"));
const timetext = ref(getLocalStorageItem('timeText'), null);
const intervalValue = ref(getLocalStorageItem('intervalValue', 1), []);
const repeatType = ref(getLocalStorageItem('repeatType', 'Forever'), null);
const selectedForDate = ref(getLocalStorageItem('selectedForDate', formatDate(new Date())));
const selectedInternalDate = ref(getLocalStorageItem('selectedInternalDate', formatDate(new Date())));
const times = ref(getLocalStorageItem('times'), null);
const updateRepeatTextHandler = (data) => {
    repeatName.value = data.repeatName;
    intervalValue.value = data.intervalValue;
    repeatType.value = data.repeatType;
    selectedForDate.value = data.selectedForDate;
    selectedInternalDate.value = data.selectedInternalDate;
    times.value = data.times;
    timetext.value = data.timeText;
};

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

const submitForm = async () => {
    const start_date = `${selectedInternalDate.value} ${timetext.value}`;
    const end_day = `${selectedForDate.value} ${timetext.value}`;
    try {
        const formData = {
            category_id: category_id.value,
            amount: amount.value,
            wallet_id: wallet_id.value,
            note: note.value,
            start_date: start_date,
            interval: repeatType.value === "Until"
                ? end_day
                : repeatType.value === "Forever"
                    ? 30
                    : times.value,
            type: repeatName.value,
            frequency: intervalValue.value,
        };

        console.log(formData);
        const response = await axios.post(route('CreateRecurringTransaction'), formData);
        if (response.data.success) {
            localStorage.clear()
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
