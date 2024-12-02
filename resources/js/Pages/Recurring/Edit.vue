<template>
    <div>
        <div v-if="isLoading" class="w-full h-screen flex items-center justify-center">
            <Loading class="size-8" />
        </div>
        <div v-else>
            <Form :action="'Save'" @submit="submitForm">
                <InputMoney :inputValue="amount.toString()" @update:inputValue="updateAmount" />
                <Select :iconSrc="categoryIcon" :selectText="selectedCategory ? selectedCategory : 'Select category'"
                    :sizeText="'16'" :getItemLabel="item => item.name" @update:selectText="updateCategory"
                    @click="goPage('SelectCategories')" />
                <Note v-model="note" fromPage="EditRecurringTransaction" />
                <Recurring v-if="isDataReady" :repeatText="repeatName" :start_date="selectedInternalDate"
                    :timeText="timetext" :times="times" :end_date="selectedForDate" :frequency="intervalValue"
                    :repeatType="repeatType" @update:repeatText="updateRepeatTextHandler" />
                <Select :iconSrc="WalletIcon" :selectText="selectedWallet ? selectedWallet : 'Select Wallet'"
                    :items="wallets" :getItemLabel="item => item.name" @click="goPage('SelectWallet')" />
                <Submit> Save</Submit>
            </Form>
        </div>
    </div>
</template>
<script setup>
import { ref, onMounted, computed } from 'vue';
import { useRouter } from 'vue-router';
import { InputMoney, Select, Note, Form, Recurring } from '@/Components/Form/Index';
import { useToast } from 'vue-toastification';
import Submit from '@/Components/Button/Submit/Index.vue';
import Loading from '@/Components/Loading/Index.vue';
import axios from 'axios';
const formatDate = (date) => {
    const day = String(date.getDate()).padStart(2, '0');
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const year = date.getFullYear();
    return `${day}/${month}/${year}`;
};
const isDataReady = computed(() => {
    console.log('isLoading:', isLoading.value);
    console.log('repeatName:', repeatName.value);
    console.log('selectedInternalDate:', selectedInternalDate.value);
    console.log('selectedforDate:', selectedForDate.value);
    console.log('timetext:', timetext.value);
    console.log('times:', times.value);
    console.log('intervalValue:', intervalValue.value);
    console.log('Type:', repeatType.value);

    return !isLoading.value &&
        repeatType.value &&
        repeatName.value &&
        selectedInternalDate.value &&
        selectedForDate.value &&
        timetext.value &&
        times.value &&
        selectedForDate.value &&
        intervalValue.value;
});

const toast = useToast();
const router = useRouter();

const getLocalStorageItem = (key, defaultValue = null) => {
    const item = localStorage.getItem(key);
    try {
        return item ? JSON.parse(item) : defaultValue;
    } catch (error) {
        return item || defaultValue;
    }
};
const isLoading = ref(true);
const wallet_id = ref(getLocalStorageItem('wallet_id', []));
const amount = ref(getLocalStorageItem('amount', '0'));
const note = ref(getLocalStorageItem('note', ''));
const selectedWallet = ref(getLocalStorageItem('selectedWallet', null));
const selectedCategory = ref(getLocalStorageItem('selectedCategory', null));
const categoryIcon = ref(getLocalStorageItem('CategoryIcon', null));
const WalletIcon = ref(getLocalStorageItem('WalletIcon', null));
const repeatName = ref(getLocalStorageItem('repeatName', 'Repeat Daily'), null);
const intervalValue = ref(getLocalStorageItem('intervalValue', null), null);
const repeatType = ref(getLocalStorageItem('repeatType', 'Forever'), null);
const selectedForDate = ref(getLocalStorageItem('selectedForDate', formatDate(new Date())));
const selectedInternalDate = ref(getLocalStorageItem('selectedInternalDate', formatDate(new Date())));
const category_id = ref(getLocalStorageItem('categoryId', []));
const times = ref(getLocalStorageItem('times', '1'));
const timetext = ref(getLocalStorageItem('timeText', "00:00 AM"));

const id = router.currentRoute.value.params.id;
const fetchTransactionData = async () => {
    try {
        const response = await axios.get(route('EditRecurringTransaction', { id }));
        console.log("Fetched data:", response.data.transactionRecurring);
        const data = response.data.transactionRecurring;
        if (!localStorage.getItem('repeatType')) repeatType.value = data.repeatType;
        if (!localStorage.getItem('selectedInternalDate')) selectedInternalDate.value = extractDate(data.start_date);
        if (!localStorage.getItem('timeText')) timetext.value = extractTime(data.start_date);
        if (!localStorage.getItem('times')) times.value = data.times || 1;
        if (!localStorage.getItem('selectedForDate')) selectedForDate.value = data.end_date ? formatDate(new Date(data.end_date)) : formatDate(new Date());
        if (!localStorage.getItem('intervalValue')) intervalValue.value = data.frequency || 1;
        if (!localStorage.getItem('repeatName')) repeatName.value = data.type;
        if (!localStorage.getItem('CategoryIcon')) categoryIcon.value = data.icon_path;
        if (!localStorage.getItem('WalletIcon')) WalletIcon.value = data.wallet_image;
        if (!localStorage.getItem('amount')) amount.value = data.amount;
        if (!localStorage.getItem('note')) note.value = data.note;
        if (!localStorage.getItem('categoryId')) category_id.value = data.category_id;
        if (!localStorage.getItem('wallet_id')) wallet_id.value = data.wallet_id;
        if (!localStorage.getItem('selectedCategory')) selectedCategory.value = data.name;
        if (!localStorage.getItem('selectedWallet')) selectedWallet.value = data.wallet_name;
    } catch (error) {
        toast.error('Failed to load recurring  transaction data. Please try again.');
    } finally {
        isLoading.value = false;
    }
};

const extractDate = (datetime) => {
    return datetime.split(' ')[0];
};

const extractTime = (datetime) => {
    return datetime.split(' ')[1] + ' ' + datetime.split(' ')[2];
};

const updateAmount = (value) => {
    amount.value = value;
};
const updateCategory = (value) => {
    selectedCategory.value = value;
};
const updateRepeatTextHandler = (data) => {
    console.log("Received data from Recurring component:", data);
    repeatName.value = data.repeatName || repeatName.value;
    intervalValue.value = data.intervalValue || intervalValue.value;
    repeatType.value = data.repeatType || repeatType.value;
    selectedForDate.value = data.selectedForDate || selectedForDate.value;
    selectedInternalDate.value = data.selectedInternalDate || selectedInternalDate.value;
    times.value = data.times || times.value;
    timetext.value = data.timeText || timetext.value;
};

const goPage = (page) => {
    router.push({ name: page, query: { id: id, fromPage: 'EditRecurringTransaction', id } });
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
            interval: repeatType.value === "Untill"
                ? end_day
                : repeatType.value === "Forever"
                    ? 30
                    : times.value,
            type: repeatName.value,
            frequency: intervalValue.value,
        };

        console.log("data", formData)
        const response = await axios.post(route('UpdateRecurringTransaction', { id }), formData);
        if (response.data.success) {
            toast.success(response.data.message);
            router.push({ name: 'RecurringTransaction' });
        } else {
            toast.error('Failed to update transaction.');
        }
    } catch (error) {
        toast.error('Error updating transaction: ' + error.message);
    }
};
onMounted(() => {
    fetchTransactionData();
});

</script>
