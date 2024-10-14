<template>
    <div>
      <Form :action="'Save'" @submit="submitForm">
        <InputMoney :inputValue="amount" @update:inputValue="amount = $event" />
        <Select :selectText="'Select category'" :sizeText="'24'"/>
        <Note v-model="note" />
        <Select :icon="'fa-regular fa-calendar'" :selectText="'Today'" />
        <Select :icon="'wallet'" :selectText="'Select wallet'" />
        <Submit> Save</Submit>
      </Form>
    </div>
  </template>

  <script setup>
  import { ref } from 'vue';
  import { InputMoney, Select, Note, Form } from '@/Components/Form/Index';
  import { useToast } from 'vue-toastification';
  import Submit from '@/Components/Button/Submit/Index.vue';

  const toast = useToast();

  const amount = ref('0');
  const note = ref('');

  const submitForm = async () => {
    try {
      const formData = {
        category_id: 1,
        amount: amount.value,
        type: 'income',
        wallet_id: 1,
        note: note.value,
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
