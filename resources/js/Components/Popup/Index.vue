<template>
    <div v-if="isVisible" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" @click.self="closePopup">
        <div class="bg-white rounded-lg shadow-lg max-w-sm p-6 text-center mx-auto">
            <img src="/assets/img/logo.png" alt="" class="w-20 mx-auto mb-4 rounded-lg">
            <h2 class="text-xl font-bold text-primary mb-2">{{ title }}</h2>
            <p class="text-secondaryText mb-4">{{ message }}</p>
            <button
                @click="closePopup"
                class="bg-primary text-white py-2 px-4 rounded hover:bg-hoverPrimary button-animate">
                OK
            </button>
        </div>
    </div>
</template>

<script setup>
import { ref } from "vue";

defineProps({
  title: {
    type: String,
    default: "Notification",
  },
  message: {
    type: String,
    default: "This feature is coming soon.",
  },
  type: {
    type: String,
    default: "coming soon",
  },
});

const isVisible = ref(false);

const openPopup = () => {
  isVisible.value = true;
};

const closePopup = () => {
  isVisible.value = false;
};

defineExpose({
  openPopup,
});
</script>
  