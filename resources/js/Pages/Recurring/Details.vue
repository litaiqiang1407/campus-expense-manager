<template>
    <div class="bg-white">
        <div v-if="loading" class="w-full h-screen flex items-center justify-center">
            <Loading class="size-8" />
        </div>  
        <div class="pl-4 mt-4 pr-4">
            <Select :iconSrc="categoryIcon" :selectText="category_name ? category_name : 'Select category'"
                :sizeText="'16'" :getItemLabel="item => item.name" />
            <InputMoney :inputValue="amount.toString()" :readonly="true" />
            <Select :iconSrc="walletIcon" :selectText="wallet_name ? wallet_name : 'Select Wallet'" />
            <button class="mt-4 mr-1 w-full p-2 bg-primary text-white text-[16px] rounded-[1000px]"
                @click="editTransaction(transactionRecurringId)">
                Edit
            </button>
            <button class="mt-4 p-2 bg-[#8B0000] w-full text-white text-[16px] rounded-[1000px]"
                @click="confirmDelete(transactionRecurringId)">
                Delete
            </button>
        </div>
    </div>
</template>

<script setup>
import { InputMoney, Select} from '@/Components/Form/Index';
import axios from 'axios';
import { ref, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import Loading from '@/Components/Loading/Index.vue';
import Swal from 'sweetalert2';

const loading = ref(true);
const amount = ref(0);
const category_name = ref(null);
const wallet_name = ref(null);
const categoryIcon = ref(null);
const walletIcon = ref(null);
const router = useRouter();
const id = router.currentRoute.value.params.id;
const fetchTransactionDetailsData = async () => {
    try {
        loading.value = true;
        const response = await axios.get(route('TransactionRecurringDetails', { id }));
        console.log("data", response.data.transactionRecurring)
        amount.value = response.data.transactionRecurring?.amount ?? 0;
        category_name.value = response.data.transactionRecurring?.category_name ?? 'Unknown';
        wallet_name.value = response.data.transactionRecurring?.wallet_name ?? 'Unknown';
        walletIcon.value = response.data.transactionRecurring?.wallet_image ?? '/path/to/default/icon.png';
        categoryIcon.value = response.data.transactionRecurring?.icon_path ?? '/path/to/default/icon.png';
    } catch (error) {
        console.error("Error fetching transaction details:", error);
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'Failed to load transaction details. Please try again later.',
        });
    } finally {
        loading.value = false;
    }
};

const editTransaction = (id) => {
    router.push({ name: 'EditRecurringTransaction', params: { id } });
};

const confirmDelete = (transactionRecurringId) => {
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
            deleteRecurringTransaction(id);
        }
    });
};

const deleteRecurringTransaction = async (id) => {
    try {
        await axios.post(route('DeleteRecurringTransaction', { id }));
        window.location.href = '/transaction/recurring';
    } catch (error) {
        console.error('Error deleting transaction:', error);
    }
    localStorage.clear();
};

onMounted(() => {
    localStorage.clear();
    fetchTransactionDetailsData();
});
</script>
