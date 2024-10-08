<template>
    <Form :action="'Create Wallet'">
        <Input :label="'Wallet name'" />
        <Select :selectText="'Change icon'" :sizeText="'28'"/>
        <InputMoney />
        <Dropdown :itemList="walletTypeList" :selectedItem="walletType" :defaultText="'Select wallet type'" />
    </Form>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { InputMoney, Select, Form, Input } from '@/Components/Form/Index';
import Dropdown from '@/Components/Dropdown/Index.vue';

const walletType = ref(null);
const walletTypeList = ref([]);
const icons = ref([]);

const props = defineProps({
  walletTypeId: {
    type: String,
    required: true,
  },
});

const fetchWalletData = async () => {
  try {
    const response = await axios.get(route('CreateWallet', { walletTypeId: props.walletTypeId }));
    walletTypeList.value = response.data.walletTypeList;
    icons.value = response.data.icons;
    walletType.value = response.data.walletType;
    console.log('Wallet created:', response.data);
  } catch (error) {
    console.error('Error creating wallet:', error);
  }
};

onMounted(fetchWalletData);
</script>