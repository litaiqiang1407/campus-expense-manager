<template>
    <Form :action="'Create Wallet'" @submit="submitForm">
        <Input v-model="walletName" :label="'Wallet name'" />
        <Select :selectText="'Change icon'" :sizeText="'28'"/>
        <InputMoney :inputValue="balance" @update:inputValue="balance = $event" />
        <Dropdown :itemList="walletTypeList" :selectedItem="walletType" v-model="walletType"  :defaultText="'Select wallet type'" />
        <Submit>Create Wallet</Submit> 
    </Form>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { InputMoney, Select, Form, Input } from '@/Components/Form/Index';
import Dropdown from '@/Components/Dropdown/Index.vue';
import Submit from '@/Components/Button/Submit/Index.vue';
import { useToast } from 'vue-toastification';

const toast = useToast();

const walletType = ref(null);
const walletTypeList = ref([]);
const icons = ref([]);
const balance = ref('0');
const walletName = ref('');

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
  } catch (error) {
    console.error('Error creating wallet:', error);
  }
};

const submitForm = async () => {
  try {
    const formData = {
      name: walletName.value,
      balance: balance.value,
      wallet_type_id: walletType.value.id,
      icon_id: 3,
    };

    const response = await axios.post(route('StoreWallet'), formData);
    
    if (response.data.success) {
      toast.success(response.data.message);
      window.location.href = route('MyWallet');
    } else {
      toast.error('Failed to create wallet.');
    }
  } catch (error) {
    toast.error('Error creating wallet: ' + error.response?.data?.message || error.message);
  }
};

onMounted(fetchWalletData);
</script>