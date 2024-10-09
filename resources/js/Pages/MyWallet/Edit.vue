<template>
    <div>
      <div  v-if="loading"><Loading/></div>
      <Form v-else :action="'Update Wallet'" @submit="submitForm">
          <Input :label="'Wallet name'" :modelValue="wallet.name" v-model="wallet.name"  />
          <Select :selectText="wallet?.icon_name || 'Change icon'" :sizeText="'28'" :iconSrc="wallet.icon_path"/>
          <InputMoney :inputValue="String(wallet.balance)" @update:inputValue="updateBalance" />
          <Dropdown :itemList="walletTypeList" :modelValue="walletTypeSelected" v-model="walletTypeSelected" :defaultText="'Select wallet type'" />
          <Submit>Update Wallet</Submit>
      </Form>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { InputMoney, Select, Form, Input } from '@/Components/Form/Index';
import Dropdown from '@/Components/Dropdown/Index.vue';
import Submit from '@/Components/Button/Submit/Index.vue';
import Loading from '@/Components/Loading/Index.vue';
import { useToast } from 'vue-toastification';

const toast = useToast();

const wallet = ref({});
const walletTypeList = ref([]);
const walletTypeSelected = ref({});
const loading = ref(false);

const props = defineProps({
  walletId: {
    type: String,
    required: true,
  },
});

const fetchWalletData = async () => {
  try {
    loading.value = true;
    const response = await axios.get(route('EditWallet', { walletId: props.walletId }));
    walletTypeList.value = response.data.walletTypes;
    wallet.value = response.data.wallet;
    walletTypeSelected.value = { id: response.data.wallet.wallet_type_id, name: response.data.wallet.walletTypeName };
  } catch (error) {
    console.error('Error creating wallet:', error);
  } finally {
    loading.value = false;
  }
};

const updateBalance = (newValue) => {
  wallet.value.balance = newValue;
};

const submitForm = async () => {
  try {
    const formData = {
      name: wallet.value.name,
      wallet_type_id: walletTypeSelected.value.id,
      balance: wallet.value.balance,
      icon_id: wallet.value.icon_id,
    };

    console.log('formData', formData);

    const response = await axios.post(route('UpdateWallet', { walletId: props.walletId }), formData);

    if (response.data.success) {
      toast.success(response.data.message);
      window.location.href = route('MyWallet');
    } else {
      toast.error('Failed to update wallet.');
    }
  } catch (error) {
    toast.error('Error updating wallet: ' + error.response?.data?.message || error.message);
  }
};


onMounted(fetchWalletData);
</script>
