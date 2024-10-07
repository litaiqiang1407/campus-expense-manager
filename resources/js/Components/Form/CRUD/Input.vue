<template>
    <div class="w-full py-4">
      <label class="text-secondaryText font-medium text-[24px] mb-2">{{ label }}
      <input 
        type="text" 
        v-model="inputValue"
        class="w-full text-primary font-semibold text-[28px] border-b-[3px] border-primary py-1 focus:outline-none"
      />
    </label>
    </div>
</template>
  
<script setup>
import { ref } from 'vue';

const inputValue = ref('');

const props = defineProps({
  label: {
    type: String,
    default: '',
  },
});
</script>

