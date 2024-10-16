<template>
    <div  @click="navigateToPage" class="button-animate rounded-full w-full h-full flex items-center justify-center bg-primary space-x-2">
        <font-awesome-icon v-if="icon" :icon="icon" class="text-white" />
        <span v-if="text" class="text-white text-[16px]">{{ text }}</span>
    </div>
</template>

<script setup>
import { useRouter } from 'vue-router';

const props = defineProps({
  destinationPage: {
    type: String,
  },
  icon: {
    type: String,
    default: 'plus',
  },
  text: {
    type: String,
    default: '',
  },
});

const router = useRouter();

const navigateToPage = () => {
  router.push({ name: props.destinationPage });
};
</script>