<template>
    <Form :action="'Create Wallet'" @submit="submitForm">
        <div class="flex items-center space-x-8">
          <Select class="w-[40px]" :selectText="''" :sizeText="'28'" @click="selectIcon" :iconSrc="iconSrc"/>
          <Input v-model="walletName" :label="''" :placeholder="'Enter wallet name...'"  />
        </div>
        <InputMoney :inputValue="balance" @update:inputValue="balance = $event" />
        <Dropdown :icon="'wallet'" :itemList="walletTypeList" :selectedItem="walletType" v-model="walletType"  :defaultText="'Select wallet type'" />
        <Submit>Create Wallet</Submit>
    </Form>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue';
import { useRouter } from 'vue-router'; 
import { InputMoney, Select, Form, Input } from '@/Components/Form/Index';
import Dropdown from '@/Components/Dropdown/Index.vue';
import Submit from '@/Components/Button/Submit/Index.vue';
import { useToast } from 'vue-toastification';

const toast = useToast();

const walletType = ref(null);
const walletTypeList = ref([]);
const icons = ref([]);
const iconSrc = ref('');
const iconID = ref('');
const balance = ref(localStorage.getItem('balance') || '0');
const walletName = ref(localStorage.getItem('walletName') || '');
const iconSelectText = ref('Change icon');  
const router = useRouter(); 

const props = defineProps({
  walletTypeId: {
    type: String,
    required: true,
  },
});

const walletTypeId = ref(props.walletTypeId);

const fetchWalletData = async () => {
  try {
    const response = await axios.get(route('CreateWallet', { walletTypeId: walletTypeId.value }));
    walletTypeList.value = response.data.walletTypeList;
    icons.value = response.data.icons;
    walletType.value = response.data.walletType;
  } catch (error) {
    console.error('Error creating wallet:', error);
  }
};

const selectIcon = () => {
  localStorage.setItem('walletName', walletName.value);
  localStorage.setItem('balance', balance.value);

  router.push({ name: 'Icon', query: { walletTypeId: walletType.value.id } });
};


const submitForm = async () => {
  try {
    const formData = {
      name: walletName.value,
      balance: balance.value,
      wallet_type_id: walletType.value.id,
      icon_id: iconID.value,
    };

    const response = await axios.post(route('StoreWallet'), formData);

    if (response.data.success) {
      toast.success(response.data.message);
      window.location.href = route('MyWallet');
      localStorage.clear(); 
    } else {
      toast.error('Failed to create wallet.');
    }
  } catch (error) {
    toast.error('Error creating wallet: ' + error.response?.data?.message || error.message);
  }
};

onMounted(() => {
  const queryIcon = router.currentRoute.value.query.icon;
  if (queryIcon) {
    const icon = JSON.parse(queryIcon);
    iconSelectText.value = icon.name;
    iconSrc.value = icon.path;
    iconID.value = icon.id;
  }

  walletName.value = localStorage.getItem('walletName') || '';
  balance.value = localStorage.getItem('balance') || '0';

  fetchWalletData();
});


</script>
