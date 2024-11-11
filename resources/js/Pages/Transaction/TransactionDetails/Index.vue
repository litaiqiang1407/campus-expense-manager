<template>
    <div>
        <div v-if="loading" class="flex w-screen items-center justify-center h-64">
            <Loading class="size-16" />
        </div>

        <div class="pl-4 mt-4">
            <InputMoney :inputValue="amount.toString()" />

            <Select :iconSrc="categoryIcon" :selectText="category_name ? category_name : 'Select category'"
                :sizeText="'16'" :getItemLabel="item => item.name" />
            <Note v-model="note" :readonly="true" />

            <DateTimePicker v-if="!loading" :icon="'fa-regular fa-calendar'" v-model="transactionDate"
                :readonly="true" />

            <Select :iconSrc="walletIcon" :selectText="wallet_name ? wallet_name : 'Select Wallet'"/>
            <button class="mt-4 mr-1 p-2 bg-primary text-white text-[16px] rounded"
                @click="editTransaction(transactionId)">
                Edit
            </button>
            <button class="mt-4 p-2 bg-primary text-white text-[16px] rounded" @click="confirmDelete(transactionId)">
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
const transactionId = router.currentRoute.value.params.transactionId;

const fetchTransactionDetailsData = async () => {
    try {
        loading.value = true;
        const response = await axios.get(route('TransactionDetails', { transactionId }));
        amount.value = response.data.transaction.amount;
        category_name.value = response.data.transaction.category_name;
        note.value = response.data.transaction.note;
        transactionDate.value = new Date(response.data.transaction.date);
        wallet_name.value = response.data.transaction.wallet_name;
        categoryIcon.value = response.data.transaction.iconPath;
        walletIcon.value = response.data.transaction.walletIcon;

    } catch (error) {
        console.error("Error fetching transaction details:", error);
    } finally {
        loading.value = false;
    }
};

const editTransaction = (transactionId) => {
    router.push({ name: 'EditTransaction', params: { transactionId } });
};

const confirmDelete = (transactionId) => {
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
            deleteTransaction(transactionId);
        }
    });
};

const deleteTransaction = async (transactionId) => {
    try {
        await axios.post(route('DeleteTransaction', { transactionId }));
        window.location.href = '/transaction';
    } catch (error) {
        console.error('Error deleting transaction:', error);
    }
};

onMounted(() => {
    fetchTransactionDetailsData();
});
</script>
