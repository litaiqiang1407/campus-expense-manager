<template>
    <div class="bg-white">
        <div v-if="loading" class="w-full h-screen flex items-center justify-center">
            <Loading class="size-8" />
        </div>
        <div class="pl-4 mt-4 pr-4">
            <InputMoney :inputValue="amount.toString()" :readonly="true" />

            <Select :iconSrc="categoryIcon" :selectText="category_name ? category_name : 'Select category'"
                :sizeText="'16'" :getItemLabel="item => item.name" />
            <Note v-model="note" :readonly="true" />

            <DateTimePicker v-if="!loading" :icon="'fa-regular fa-calendar'" v-model="transactionDate"
                :readonly="true" />

            <Select :iconSrc="walletIcon" :selectText="wallet_name ? wallet_name : 'Select Wallet'" />
            <button class="mt-4 mr-1 w-full p-2 bg-primary text-white text-[16px] rounded-[1000px]"
                @click="editTransaction(id)">
                Edit
            </button>
            <button class="mt-4 p-2 bg-[#8B0000] w-full text-white text-[16px] rounded-[1000px]"
                @click="confirmDelete(id)">
                Delete
            </button>
        </div>
    </div>
</template>

<script setup>
import { InputMoney, Select, Note, DateTimePicker } from '@/Components/Form/Index';
import axios from 'axios';
import { ref, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import Loading from '@/Components/Loading/Index.vue';
import Swal from 'sweetalert2';

const loading = ref(true);
const amount = ref(0);
const category_name = ref(null);
const note = ref(null);
const transactionDate = ref(null);
const wallet_name = ref(null);
const categoryIcon = ref(null);
const walletIcon = ref(null);
const router = useRouter();
const id = router.currentRoute.value.params.id;

const fetchTransactionDetailsData = async () => {
    try {
        loading.value = true;
        const response = await axios.get(route('TransactionDetails', { id }));
        amount.value = response.data.transaction.amount;
        category_name.value = response.data.transaction.category_name;
        note.value = response.data.transaction.note;
        transactionDate.value = new Date(response.data.transaction.date);
        wallet_name.value = response.data.transaction.wallet_name;
        categoryIcon.value = response.data.transaction.iconPath;
        walletIcon.value = response.data.transaction.walletIcon;
        localStorage.setItem('CategoryIcon', categoryIcon.value);
        localStorage.setItem('WalletIcon', walletIcon.value);
    } catch (error) {
        console.error("Error fetching transaction details:", error);
    } finally {
        loading.value = false;
    }
};

const editTransaction = (id) => {
    router.push({ name: 'EditTransaction', params: { id } });
};

const confirmDelete = (id) => {
    Swal.fire({
        title: 'Are you sure?',
        text: 'You won\'t be able to revert this!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#00BC2A',
        cancelButtonColor: '#FF2121',
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'No, cancel!',
    }).then((result) => {
        if (result.isConfirmed) {
            deleteTransaction(id);
        }
    });
};

const deleteTransaction = async (id) => {
    try {
        await axios.post(route('DeleteTransaction', { id }));
        window.location.href = '/transaction';
    } catch (error) {
        console.error('Error deleting transaction:', error);
    }
};

onMounted(() => {
    fetchTransactionDetailsData();
});
</script>
