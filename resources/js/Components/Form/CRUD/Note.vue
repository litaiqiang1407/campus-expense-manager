<template>
    <div class="flex w-full space-x-8 items-center py-4">
      <div class="w-[32px] flex items-center justify-center">
        <font-awesome-icon icon="fa-regular fa-comment" class="pl-2 text-black text-[36px]" />
      </div>

      <textarea
        v-model="noteContent"
        placeholder="Write Note"
        class="pl-2.5 text-secondaryText text-[14px] w-full border rounded"
        @focus="goToNote"
      ></textarea>
    </div>
  </template>

  <script setup>
  import { ref, defineProps, watch, defineEmits } from 'vue';
  import { useRouter } from 'vue-router';

  const props = defineProps({
    modelValue: {
      type: String,
      default: ''
    }
  });

  const emit = defineEmits(['update:modelValue']);
  const noteContent = ref(props.modelValue);
  const router = useRouter();

  // Theo dõi sự thay đổi trong noteContent và phát ra giá trị mới
  watch(noteContent, (newValue) => {
    emit('update:modelValue', newValue);
  });

  // Theo dõi sự thay đổi trong modelValue từ cha và cập nhật noteContent
  watch(() => props.modelValue, (newValue) => {
    noteContent.value = newValue;
  });

  // Hàm chuyển hướng đến trang ghi chú
  function goToNote() {
    router.push({ name: 'Note', query: { returnPath: '/transaction/create', note: noteContent.value } });
  }
  </script>
