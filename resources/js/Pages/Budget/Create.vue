<template>
  <Form :action="'Save'" @submit="submitForm">
    <Select :selectText="'Select category'" :sizeText="'24'" />
    <InputMoney :inputValue="amount" @update:inputValue="amount = $event" />
    
    <div class="relative">
      <Select 
        :icon="'fa-regular fa-calendar'" 
        :selectText="formattedDateOption || 'Today'"
        :isDateSelect="true"  
      @show-date-options="showDateOptions = true" />

      <div v-if="showDateOptions" class="fixed inset-0 bg-black bg-opacity-50 flex items-end justify-center z-50" @click="showDateOptions = false"></div>
      <!-- Updated date options with slide-up effect -->
      <div v-if="showDateOptions" class="fixed inset-x-0 bottom-0 z-50 w-full bg-white border-t border-gray-300 rounded-t-lg shadow-lg transform transition-transform duration-300 ease-out"
           :class="{'translate-y-full': !showDateOptions, 'translate-y-0': showDateOptions}">
        <div class="p-4">
          <h3 class="text-2xl font-bold tracking-wide">Select time range</h3>
            <ul class="mt-2">
              <li v-for="option in dateOptions" :key="option.value" @click="selectDateOption(option.value)" class="flex items-center py-2 cursor-pointer text-lg">
                <input type="radio" :value="option.value" v-model="selectedDateOption" class="mr-2 h-5 w-5" />
                <span class="text-lg font-medium tracking-normal">{{ option.label }}</span>
              </li>
            </ul>
            <div v-if="selectedDateOption === 'Custom'" class="mt-4 flex items-center justify-center">
              <div class="flex items-center">
                <span class="mr-2 text-lg font-medium tracking-normal">From</span>
                <input type="date" v-model="customFrom" class="border-gray-300 rounded-md shadow-sm text-lg" />
              </div>
              <span class="mx-2 text-lg font-medium tracking-normal">-</span>
              <div class="flex items-center">
                <span class="mr-2 text-lg font-medium tracking-normal">To</span>
                <input type="date" v-model="customTo" class="border-gray-300 rounded-md shadow-sm text-lg" />
              </div>
            </div>
        </div>
         <div class="flex justify-center p-4 border-t border-gray-200 space-x-4">
           <button @click="showDateOptions = false" class="flex-1 px-6 py-3 text-gray-500 bg-primaryBackground border border-gray-300 rounded-full text-lg font-medium">Cancel</button>
          <button @click="applyDateRange" class="flex-1 px-6 py-3 text-white bg-primary rounded-full text-lg font-medium">Save</button>
        </div>
      </div>
    </div>
    
    <router-link to="/select-wallet">
      <Select :icon="'wallet'" :selectText="'Total'" /> 
    </router-link>
    <Submit>Create Budget</Submit>
  </Form>
</template>


<script setup>
import { ref, computed } from 'vue';
import { InputMoney, Select, Note, Form } from '@/Components/Form/Index';
import Submit from '@/Components/Button/Submit/Index.vue';
import { useToast } from 'vue-toastification';
import axios from 'axios';
import { useRouter } from 'vue-router';

const toast = useToast();
const router = useRouter();

const amount = ref('0');
const showDateOptions = ref(false);
const selectedDateOption = ref('week'); 
const customFrom = ref('');
const customTo = ref('');
const formattedDateRange = ref(''); 

const formatDate = (date) => date.toLocaleDateString('en-GB', { day: '2-digit', month: '2-digit' });

const today = new Date();
const dayOfWeek = today.getDay();
const startOfWeek = new Date(today);
const endOfWeek = new Date(today);

startOfWeek.setDate(today.getDate() - (dayOfWeek === 0 ? 6 : dayOfWeek - 1));
endOfWeek.setDate(startOfWeek.getDate() + 6);

const startOfMonth = new Date(today.getFullYear(), today.getMonth(), 1);
const endOfMonth = new Date(today.getFullYear(), today.getMonth() + 1, 0);

const startOfQuarter = new Date(today.getFullYear(), Math.floor(today.getMonth() / 3) * 3, 1);
const endOfQuarter = new Date(startOfQuarter.getFullYear(), startOfQuarter.getMonth() + 3, 0);

const startOfYear = new Date(today.getFullYear(), 0, 1);
const endOfYear = new Date(today.getFullYear(), 11, 31);

const dateOptions = computed(() => [
  { value: 'week', label: `This week (${formatDate(startOfWeek)} - ${formatDate(endOfWeek)})` },
  { value: 'month', label: `This month (${formatDate(startOfMonth)} - ${formatDate(endOfMonth)})` },
  { value: 'quarter', label: `This quarter (${formatDate(startOfQuarter)} - ${formatDate(endOfQuarter)})` },
  { value: 'year', label: `This year (01/01/${today.getFullYear()} - 31/12/${today.getFullYear()})` },
  { value: 'Custom', label: 'Custom' }
]);

const formattedDateOption = computed(() => {
  if (selectedDateOption.value === 'Custom' && customFrom.value && customTo.value) {
      return `${formatDate(new Date(customFrom.value))} - ${formatDate(new Date(customTo.value))}`;
  }
  const selectedOption = dateOptions.value.find(option => option.value === selectedDateOption.value);
  return selectedOption ? selectedOption.label : 'Select time range';
});

const toggleDateOptions = () => {
  showDateOptions.value = !showDateOptions.value;
};

const selectDateOption = (option) => {
  selectedDateOption.value = option;
};

const applyDateRange = () => {
  if (selectedDateOption.value === 'Custom' && customFrom.value && customTo.value) {
      const formattedFrom = formatDate(new Date(customFrom.value));
      const formattedTo = formatDate(new Date(customTo.value));
      selectedDateOption.value = 'custom'; 
      formattedDateRange.value = `${formattedFrom} - ${formattedTo}`; 
  }
  showDateOptions.value = false;
};

const formatDateForServer = (date) => {
  if (!date) return null;
  const d = new Date(date);
  const day = String(d.getDate()).padStart(2, '0'); 
  const month = String(d.getMonth() + 1).padStart(2, '0'); 
  const year = d.getFullYear();
  return `${day}/${month}/${year}`; 
};

const submitForm = async () => {
  const formData = {
      amount: amount.value.trim(),
      time_range: selectedDateOption.value, 
      wallet_id: 7, 
      category_id: 1,
      custom_from: selectedDateOption.value === 'custom' ? formatDateForServer(customFrom.value) : null,
      custom_to: selectedDateOption.value === 'custom' ? formatDateForServer(customTo.value) : null,
  };

  console.log('Form Data:', formData);

  try {
      const response = await axios.post(route('StoreBudget'), formData);

      if (response.data.success) {
          toast.success(response.data.message);
          const redirectRoute = response.data.userHasWallet ? 'Home' : 'Budget'; 
          router.push({ name: redirectRoute });
      } else {
          toast.error('Failed to create budget.');
      }
  } catch (error) {
      console.error('Server Validation Error:', error.response?.data);
      toast.error('Error creating budget: ' + (error.response?.data?.message || error.message));
  }
};
</script>
