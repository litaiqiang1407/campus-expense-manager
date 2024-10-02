<template>
    <div class="h-screen flex flex-col items-center justify-center"> 
      <img :src="imageUrl" alt="No Data" class="w-24 h-auto" />
      <p class="text-center mt-2 text-sm">{{ message }}</p>
    </div>
  </template>

  <script setup>
  import { defineProps } from 'vue';

  const props = defineProps({
    message: {
      type: String,
      required: true
    },
    imageUrl: {
      type: String,
      default: () => '/assets/img/withoutdata.png'
    }
  });
  </script>
