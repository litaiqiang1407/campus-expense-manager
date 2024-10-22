<template>
    <div class="w-full py-3">
      <label class="text-secondaryText font-medium text-[16px] mb-2">{{ label }}
      <input
        type="text"
        :value="modelValue"
        @input="$emit('update:modelValue', $event.target.value)"
        :placeholder="placeholder"
        class="w-full text-primary font-semibold text-[16px] border-b-[3px] border-primary py-1 focus:outline-none"
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
  modelValue: {
    type: String,
    default: '',
  },
  placeholder: {
    type: String,
    default: '',
  },
});
</script>

