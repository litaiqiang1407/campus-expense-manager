<template>
    <div>
        <Form :action="'Save'" @submit="submitForm">
            <InputMoney :inputValue="amount" @update:inputValue="amount = $event" />
            <Select :selectText="selectedCategory ? selectedCategory.name : 'Select category'" :sizeText="'24'"
                :items="categories" :getItemLabel="item => item.name" @update:selectText="selectedCategory = $event" />
            <Note v-model="note" />
            <!-- Date Picker Input -->
            <DateTimePicker :icon="'fa-regular fa-calendar'" v-model="date" />
            <Select :icon="'wallet'" :selectText="selectedWallet ? selectedWallet.name : 'Select wallet'"
                :items="wallets" :getItemLabel="item => item.name" @update:selectText="selectedWallet = $event" />
            <Submit> Save</Submit>
        </Form>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { InputMoney, Select, Note, Form, DateTimePicker } from '@/Components/Form/Index';
import { useToast } from 'vue-toastification';
import Submit from '@/Components/Button/Submit/Index.vue';

const toast = useToast();
const categories = ref([]);
const wallets = ref([]);
const amount = ref('0');
const note = ref([]);
const date = ref([]);
const selectedWallet = ref(null);
const selectedCategory = ref(null);

const fetchCreateTransactionData = async () => {
    try {
        const response = await axios.get(route('CreateTransaction'));
        categories.value = response.data.categories;
        wallets.value = response.data.wallets;
    } catch (error) {
        console.error('Error creating wallet:', error);
    }
};

const submitForm = async () => {
    try {
        const formData = {
            category_id: selectedCategory.value.id,
            amount: amount.value,
            wallet_id: selectedWallet.value.id,
            note: note.value,
            date: date.value && date.value.length > 0 ? date.value : new Date().toISOString().split('T')[0]
        };

        console.log(formData.date);  

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
