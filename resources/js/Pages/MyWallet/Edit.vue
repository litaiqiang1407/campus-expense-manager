<template>
    <div  v-if="loading"><Loading/></div>
    <Form v-else :action="'Update Wallet'">
        <Input :label="'Wallet name'" :inputValue="wallet.name" />
        <Select :selectText="wallet?.icon_name || 'Change icon'" :sizeText="'28'" :iconSrc="wallet.icon_path"/>
        <InputMoney :inputValue="wallet.balance" />
        <Dropdown :itemList="walletTypeList" :selectedItem="walletTypeSelected" :defaultText="'Select wallet type'" />
    </Form>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { InputMoney, Select, Form, Input } from '@/Components/Form/Index';
import Dropdown from '@/Components/Dropdown/Index.vue';
import Loading from '@/Components/Loading/Index.vue';

const wallet = ref({});
const walletTypeList = ref([]);
const walletTypeSelected = ref({name: ''});
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
    walletTypeSelected.value = { name: response.data.wallet.walletTypeName };
  } catch (error) {
    console.error('Error creating wallet:', error);
  } finally {
    loading.value = false;
  }
};

onMounted(fetchWalletData);
</script>