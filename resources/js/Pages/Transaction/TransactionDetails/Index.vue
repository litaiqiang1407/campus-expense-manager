<template>
    <div>
        <div v-if="loading" class="flex w-screen items-center justify-center h-64">
            <Loading class="size-16" />
        </div>
        <Form :action="'Save'" @submit="submitForm">
            <InputMoney :inputValue="amount"/>

            <Select :selectText="category_name ? category_name : 'Select category'" :sizeText="'16'" :getItemLabel="item => item.name"/>

            <Note v-model="note"/>
            <DateTimePicker v-if="!loading" :icon="'fa-regular fa-calendar'" v-model="transactionDate" />

            <Select :iconSrc="null" :selectText="selectedWallet ? selectedWallet : 'Select Wallet'" :items="wallets"
                :getItemLabel="item => item.name"/>

            <Submit> Save</Submit>
        </Form>
    </div>
</template>

<script setup>
import Submit from '@/Components/Button/Submit/Index.vue';
import { InputMoney, Select, Note, Form, DateTimePicker } from '@/Components/Form/Index';
import axios from 'axios';
import { ref, onMounted } from 'vue';
import { useRouter } from 'vue-router';

const category_name = ref(null);

const router = useRouter();
const transactionId = router.currentRoute.value.params.transactionId;

const fetchTransactionDetailsData = async () => {
    try {
        const response = await axios.get(route('TransactionDetails', { transactionId }));
        console.log('data', response.data.transaction.category_name);

        // Gán giá trị category_name từ response vào ref
        category_name.value = response.data.transaction.category_name;
    } catch (error) {
        console.error("Error fetching transaction details:", error);
    } finally {
        loading.value = false;  // Tắt loading khi có dữ liệu
    }
};

onMounted(() => {
    fetchTransactionDetailsData();  // Gọi API khi component mount
});
</script>
