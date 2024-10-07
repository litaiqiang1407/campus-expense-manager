<template>
    <div class="w-full py-4">
      <label class="text-secondaryText font-medium text-[20px] mb-2">{{ label }}
      <input 
        type="text" 
        :value="inputValue"
        @input="$emit('update:inputValue', $event.target.value)"
        class="w-full text-primary font-semibold text-[28px] border-b-[3px] border-primary py-1 focus:outline-none"
      />
    </label>
    </div>
</template>
  
<script setup>
const props = defineProps({
  label: {
    type: String,
    default: '',
  },
  inputValue: {
    type: String,
    default: '',
  },
});
</script>

