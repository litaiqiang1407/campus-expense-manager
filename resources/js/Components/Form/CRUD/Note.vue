<template>
    <div class="flex w-full space-x-8 items-center py-4">
      <div class="w-[32px] flex items-center justify-center">
        <font-awesome-icon icon="fa-regular fa-comment" class="text-black text-[24px]" />
      </div>

      <textarea
        v-model="noteContent"
        placeholder="Write Note"
        class="pl-2.5 text-secondaryText text-[14px] w-full border rounded"
      ></textarea>
    </div>
  </template>

  <script setup>
  import { ref, defineProps, watch, defineEmits } from 'vue';

  const props = defineProps({
    modelValue: {
      type: String,
      default: ''
    }
  });

  const noteContent = ref(props.modelValue);

  const emit = defineEmits();

  watch(noteContent, (newValue) => {
    emit('update:modelValue', newValue);
  });

  watch(() => props.modelValue, (newValue) => {
    noteContent.value = newValue;
  });
  </script>
