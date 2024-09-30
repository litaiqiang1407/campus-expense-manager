<template>
  <div class="flex items-center justify-between fixed bottom-0 w-screen">
    <!-- Home button -->
    <button 
      class="w-1/5 flex flex-col items-center space-y-[4px] py-4 px-2"
      :class="activeButton === 'Home' ? 'text-black' : 'text-[#A7A7A7]'" 
      @click="setActive('Home')"
    >
      <font-awesome-icon icon="house" />
      <span class="font-semibold text-[10px]">Home</span>
    </button>

    <!-- Transaction button -->
    <button 
      class="w-1/5 flex flex-col items-center space-y-[4px] py-4 px-2"
      :class="activeButton === 'Transaction' ? 'text-black' : 'text-[#A7A7A7]'" 
      @click="setActive('Transaction')"
    >
      <font-awesome-icon icon="wallet" />
      <span class="font-semibold text-[10px]">Transaction</span>
    </button>

    <!-- Add button (center) -->
    <button 
      class="w-1/5 flex flex-col items-center text-white"
      @click="setActive('Add')"
    >
      <div 
        :class="activeButton === 'Add' ? 'bg-[#00A924]' : 'bg-[#00BC2A]'" 
        class="rounded-full w-12 h-12 flex items-center justify-center"
      >
        <font-awesome-icon icon="plus" class="text-white" />
      </div>
    </button>

    <!-- Budget button -->
    <button 
      class="w-1/5 flex flex-col items-center space-y-[4px] py-4 px-2"
      :class="activeButton === 'Budget' ? 'text-black' : 'text-[#A7A7A7]'" 
      @click="setActive('Budget')"
    >
      <font-awesome-icon icon="money-bills" />
      <span class="font-semibold text-[10px]">Budget</span>
    </button>

    <!-- Account button -->
    <button 
      class="w-1/5 flex flex-col items-center space-y-[4px] py-4 px-2"
      :class="activeButton === 'Account' ? 'text-black' : 'text-[#A7A7A7]'" 
      @click="setActive('Account')"
    >
      <font-awesome-icon icon="user" />
      <span class="font-semibold text-[10px]">Account</span>
    </button>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router'; 

const router = useRouter(); 

const activeButton = ref('Home'); 

const setActive = (button) => {
  activeButton.value = button;
  router.push({ name: button });
};
</script>
