<template>
    <div class="h-[calc(100vh-64px)] bg-white">
      <div v-if="loading" class="flex w-screen items-center justify-center h-64">
        <Loading class="size-16"/>
      </div>
      <Form v-else :action="'Update Wallet'" @submit="submitForm">
          <div class="flex items-center pb-2">
            <Select  :selectText="''" :iconSrc="selectedIcon?.path || iconPath" @click="selectIcon"/>
            <Input :label="'Wallet name'" :modelValue="wallet.name" v-model="wallet.name"  />
          </div>
          <InputMoney :inputValue="String(wallet.balance)" @update:inputValue="wallet.balance = $event" />
          <Dropdown :itemList="walletTypeList" :modelValue="walletTypeSelected" v-model="walletTypeSelected" :defaultText="'Select wallet type'" />
          <Submit>{{ isEditing ? 'Updating...' : 'Update Wallet' }}</Submit>
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
import { useRouter } from 'vue-router';
import { goSelect } from '@/Helpers/Helpers';

const toast = useToast();
const router = useRouter();

const wallet = ref({});
const walletTypeList = ref([]);
const walletTypeSelected = ref('');
const loading = ref(false);
const isEditing = ref(false)
const selectedIcon = ref(JSON.parse(localStorage.getItem('selectedIcon')) || {});
const iconPath = ref("")

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
    walletTypeSelected.value = response.data.wallet.wallet_type_name;
    iconPath.value = response.data.wallet.icon_path;

    wallet.value.name = localStorage.getItem('walletName') || wallet.value.name;
    wallet.value.balance = localStorage.getItem('balance') || wallet.value.balance;
    walletTypeSelected.value = localStorage.getItem('walletType') || walletTypeSelected.value;

  } catch (error) {
    console.error('Error creating wallet:', error);
  } finally {
    loading.value = false;
  }
};

const submitForm = async () => {
  try {
    const formData = {
      name: wallet.value.name,
      wallet_type_name: walletTypeSelected.value,
      balance: wallet.value.balance,
      icon_id: selectedIcon.value.id,
    };

    isEditing.value = true

    const response = await axios.post(route('UpdateWallet', { walletId: props.walletId }), formData);

    if (response.data.success) {
      toast.success(response.data.message);
      window.location.href = route('MyWallet');
      localStorage.clear();
    } else {
      toast.error('Failed to update wallet.');
      isEditing.value = false
    }
  } catch (error) {
    toast.error('Error updating wallet: ' + error.response?.data?.message || error.message);
    isEditing.value = false
  }
};

const selectIcon = () => {
  localStorage.setItem('walletName', wallet.value.name);
  localStorage.setItem('balance', wallet.value.balance);
  localStorage.setItem('walletType', walletTypeSelected.value);

  goSelect(router, 'icon')
};
onMounted(fetchWalletData);
</script>
