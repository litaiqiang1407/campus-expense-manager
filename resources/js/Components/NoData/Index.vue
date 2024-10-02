<template>
    <div class="flex flex-col items-center justify-center" :style="{ minHeight: 'calc(100vh - 52px)' }">
      <img :src="imageUrl" alt="No Data" class="w-32 h-auto" />
      <p class="text-center mt-8 font-semibold">{{ message }}</p>
    </div>
</template>

<script setup>
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

