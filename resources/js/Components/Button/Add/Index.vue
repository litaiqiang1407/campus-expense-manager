<template>
    <div  @click="navigateToPage" class="rounded-full w-full h-full flex items-center justify-center bg-primary">
        <font-awesome-icon v-if="icon" icon="plus" class="text-white" />
        <span v-if="text" class="text-white">{{ text }}</span>
    </div>
</template>

<script setup>
import { useRouter } from 'vue-router';

const props = defineProps({
  destinationPage: {
    type: String,
    required: true,
  },
  icon: {
    type: String,
    default: '',
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