<template>
    <div>
        <Form :action="'Save'" @submit="submitForm">
            <InputMoney :inputValue="amount" @update:inputValue="amount = $event" />
            <Select :selectText="'Select category'" :sizeText="'24'"/>
            <Note />
            <Select :icon="'fa-regular fa-calendar'" :selectText="'Today'" />
            <Select :icon="'wallet'" :selectText="'Select wallet'" />
            <Submit> Save</Submit>
        </Form>
        <!-- <div class="flex justify-center">
            <div class="mx-4 w-full flex justify-center items-center bg-secondaryText/40 rounded-[25px]">
            <p class="text-[14px] py-2 font-[500] text-black/60">
                Save
            </p>
        </div>
        </div> -->
    </div>
</template>

<script setup>
import { ref } from 'vue';
import { InputMoney, Select, Note, Form } from '@/Components/Form/Index';
import { useToast } from 'vue-toastification';
import Submit from '@/Components/Button/Submit/Index.vue';

const toast = useToast();

const amount = ref('0');

const submitForm = async () => {
  try {
    const formData = {
      category_id: 1,
      amount: amount.value,
      type:'income',
      wallet_id: 1,
      note: 'Test add transaction',
    };

    const response = await axios.post(route('StoreTransaction'), formData);

    if (response.data.success) {
      toast.success(response.data.message);
      window.location.href = route('Transaction');
    } else {
      toast.error('Failed to create Transaction.');
    }
  } catch (error) {
    toast.error('Error creating Transaction: ' + error.response?.data?.message || error.message);
  }
};
</script>
