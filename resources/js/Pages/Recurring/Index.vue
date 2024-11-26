<template>
  <div class="bg-primaryBackground">
    <!-- Header Div -->
    <div class="flex justify-between items-center bg-white p-4 pb-14 border-b border-gray-300">
      <h1 class="text-lg font-bold">Recurring transactions</h1>
      <div class="flex items-center space-x-4">
        <button @click="selectWallet">
          <img :src="wallet?.icon_path || '/assets/img/earth.png'" alt="Wallet" class="size-8">
        </button>
      </div>
    </div>
    <div class="pl-4 relative -translate-y-5" @click="goPage('AddRecurringTransaction')">
      <svg width="40" height="40" xmlns="http://www.w3.org/2000/svg">
        <!-- Circle background -->
        <circle cx="20" cy="20" r="20" fill="green" />
        <!-- Plus symbol -->
        <rect x="18" y="10" width="4" height="20" fill="white" />
        <rect x="10" y="18" width="20" height="4" fill="white" />
      </svg>
    </div>
  </div>

  <!-- Content Div -->
  <div class="flex items-center bg-primaryBackground p-4  ">

    <!-- Description Text -->
    <p class="text-gray-600 font-semibold pr-1">
      Create recurring transactions that will be automatically added in future.
    </p>
  </div>

  <div class="h-screen flex flex-col items-center justify-center">
    <img :src="imageUrl" alt="No Data" class="w-24 h-auto" />
    <p class="text-center mt-2 text-sm">{{ message }}</p>
    <p class="font-bold">No recurring transactions</p>
    <p class="font-semibold text-secondaryText">Tap
      <svg width="50" height="50" xmlns="http://www.w3.org/2000/svg">
        <!-- Dòng ngang -->
        <rect x="10" y="22" width="30" height="6" fill="black" />
        <!-- Dòng dọc -->
        <rect x="22" y="10" width="6" height="30" fill="black" />
      </svg>
      to add one
    </p>

      <Add v-if="action" :text="actionText" :destinationPage="destinationPage" />
    </div>




</template>

<script setup>
import { Add } from '@/Components/Button/Index';
import { useRouter } from 'vue-router';
const router = useRouter();
const props = defineProps({
  message: {
    type: String,
    required: true
  },
  imageUrl: {
    type: String,
    default: () => '/assets/img/withoutdata.png'
  },
  action: {
    type: Boolean,
    default: false
  },
  actionText: {
    type: String,
    default: ''
  },
  destinationPage: {
    type: String,
    default: ''
  }
});
const goPage = (pagename) => {
    router.push({name: pagename})
}
</script>
