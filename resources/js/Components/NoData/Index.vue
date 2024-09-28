<template>
    <div class="no-data-container flex flex-col items-center justify-center">
      <img :src="imageUrl" alt="No Data" class="no-data-image" />
      <p class="no-data-text text-center mt-2">{{ message }}</p>
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

  <style scoped>
  .no-data-container {
  }

  .no-data-image {
    width: 100px;
    height: auto;
  }

  .no-data-text {
  }
  </style>
