<template>
    <div class="min-h-[calc(100vh-72px)] bg-white">
      <div v-if="isFirstTime" class="w-full flex flex-col items-center p-4 mt-10">
        <h2 class="text-black text-[24px] font-bold">First, create a wallet</h2>
        <p class="text-center text-secondaryText">To get started, please create your first wallet to manage your transactions and track your finances.</p>
      </div>
      <Form :action="'Create Wallet'" @submit="submitForm">
          <div class="flex flex-col items-center">
            <Select :selectText="''" @click="selectIcon" :iconSrc="selectedIcon?.path || '/assets/icon/income/salary.png'" :hideText="true"/>
            <span class="text-primary font-semibold text-[14px]">{{ selectedIcon?.name || 'Select wallet icon'}}</span>
          </div>
          <Input v-model="walletName" :label="''" :placeholder="'Wallet name...'"  />
          <InputMoney :inputValue="balance" @update:inputValue="balance = $event" />
          <Dropdown :icon="'wallet'" :itemList="walletTypeList" :selectedItem="walletType" v-model="walletType"  :defaultText="'Select wallet type'" />
          <Submit>Create Wallet</Submit>
      </Form>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import { InputMoney, Select, Form, Input } from '@/Components/Form/Index';
import Dropdown from '@/Components/Dropdown/Index.vue';
import Submit from '@/Components/Button/Submit/Index.vue';
import { useToast } from 'vue-toastification';
import { goSelect } from '@/Helpers/Helpers';

const toast = useToast();
const router = useRouter();

const isFirstTime = ref(false);
const walletTypeList = ref([]);
const icons = ref([]);
const balance = ref(localStorage.getItem('balance') || '0');
const walletName = ref(localStorage.getItem('walletName') || '');
const selectedIcon = ref(JSON.parse(localStorage.getItem('selectedIcon')) || {});

const props = defineProps({
  walletType: {
    type: String,
    required: true,
  },
});

const walletType = ref(props.walletType || 'Basic');

const fetchWalletData = async () => {
  try {
    const response = await axios.get(route('CreateWallet'));
    walletTypeList.value = response.data.walletTypeList;
    icons.value = response.data.icons;
    isFirstTime.value = response.data.isFirstTime;
  } catch (error) {
    console.error('Error creating wallet:', error);
  }
};

const selectIcon = () => {
  localStorage.setItem('walletName', walletName.value);
  localStorage.setItem('balance', balance.value);
  localStorage.setItem('walletType', walletType.value);

  goSelect(router, 'icon')
};


const submitForm = async () => {
  const errors = [];
  if (!walletName.value.trim()) errors.push('Wallet name is required.');
  if (!walletType.value) errors.push('Please select a wallet type.');
  if (!selectedIcon.value.id) errors.push('Please select a wallet icon.');

  if (errors.length) {
    errors.forEach(toast.error);
    return;
  }

  const formData = {
    name: walletName.value.trim(),
    balance: balance.value,
    wallet_type_name: walletType.value,
    icon_id: selectedIcon.value.id,
  };

  try {
    const response = await axios.post(route('StoreWallet'), formData);

    if (response.data.success) {
      toast.success(response.data.message);
      const redirectRoute = response.data.userHasWallet ? 'Home' : 'MyWallet';
      router.push({ name: redirectRoute });
      localStorage.clear();
    } else {
      toast.error(response.data.message);
    }
  } catch (error) {
    toast.error('Error creating wallet: ' + error.response?.data?.message || error.message);
  }
};

onMounted(() => {
  walletName.value = localStorage.getItem('walletName') || '';
  balance.value = localStorage.getItem('balance') || '0';

  fetchWalletData();
});
</script>
