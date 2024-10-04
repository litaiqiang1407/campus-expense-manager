<template>
    <!-- <div class="m-0 mt-2 p-0 bg-white">
      <div class="flex items-center justify-between px-4 py-2 relative">
        <div class="flex items-start space-x-2">
          <div class="flex flex-row items-start">
            <h1 class="text-3xl">{{ dayUse }}</h1>
            <div class="flex flex-col items-start pt-1 pl-2">
              <p class="text-[10px]">Today</p>
              <p class="text-[10px]">September</p>
            </div>
          </div>
        </div>
        <p class="text-sm">{{ totalFlow }}</p>
      </div>
      <div class="px-4">
        <span class="block w-full h-px bg-[#A7A7A7] mx-auto"></span>
      </div>
      <div class="flex items-center px-4 py-2 relative">
        <img :src="iconPath" alt="Icon" class="w-8 h-8">
        <span class="ml-2 font-semibold text-[14px]">{{ iconName }}</span>
      </div>
    </div> -->
</template>

<script setup>
import { Transaction } from '../../../Index';

//const props = defineProps(['dayUse', 'totalFlow', 'iconName', 'iconPath']);
const props = defineProps({
    transactions: Array
})
console.log(props.transactions)
</script>
