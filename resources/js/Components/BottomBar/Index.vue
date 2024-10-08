<template>
  <div class="flex items-center justify-between fixed bottom-0 w-screen bg-white">
    <!-- Home button -->
    <button
      class="w-1/5 flex flex-col items-center space-y-[4px] py-4 px-2"
      :class="activeButton === 'Home' ? 'text-black' : 'text-secondaryText'"
      @click="setActive('Home')"
    >
      <font-awesome-icon icon="house" />
      <span class="font-semibold text-[10px]">Home</span>
    </button>

    <!-- Transaction button -->
    <button
      class="w-1/5 flex flex-col items-center space-y-[4px] py-4 px-2"
      :class="activeButton === 'Transaction' ? 'text-black' : 'text-secondaryText'"
      @click="setActive('Transaction')"
    >
      <font-awesome-icon icon="wallet" />
      <span class="font-semibold text-[10px]">Transaction</span>
    </button>

    <!-- Add button (center) -->
    <button
      class="w-1/5 flex flex-col items-center text-white"
    >
      <div class="w-12 h-12">
        <AddButton :destinationPage="'CreateTransaction'" :icon="'plus'"/>
      </div>
    </button>

    <!-- Budget button -->
    <button
      class="w-1/5 flex flex-col items-center space-y-[4px] py-4 px-2"
      :class="activeButton === 'Budget' ? 'text-black' : 'text-secondaryText'"
      @click="setActive('Budget')"
    >
      <font-awesome-icon icon="money-bills" />
      <span class="font-semibold text-[10px]">Budget</span>
    </button>

    <!-- Account button -->
    <button
      class="w-1/5 flex flex-col items-center space-y-[4px] py-4 px-2"
      :class="activeButton === 'Account' ? 'text-black' : 'text-secondaryText'"
      @click="setActive('Account')"
    >
      <font-awesome-icon icon="user" />
      <span class="font-semibold text-[10px]">Account</span>
    </button>
  </div>
</template>

<script setup>
import { ref, watch } from 'vue';
import { useRouter, useRoute } from 'vue-router';
import AddButton from '../Button/Add/Index.vue'

const router = useRouter();
const route = useRoute();

const activeButton = ref(route.name);

const setActive = (button) => {
  activeButton.value = button;
  router.push({ name: button });
};

watch(route, (newRoute) => {
  activeButton.value = newRoute.name;
});
</script>
