<template>
    <div class="h-screen flex flex-col items-center justify-center">
      <img :src="imageUrl" alt="No Data" class="w-24 h-auto" />
      <p class="text-center mt-2 text-sm">{{ message }}</p>
      <div class="w-full h-10 px-[20px] my-4">
        <Add v-if="action" :text="actionText" :destinationPage="destinationPage" />
      </div>
    </div>
</template>

<script setup>
import { Add } from '@/Components/Button/Index';
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
</script>
