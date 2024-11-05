<template>
  <div class="relative w-full">
      <label
          :class="{
              'transform -translate-y-6 left-0 text-primary text-sm': isFocused || modelValue,
              'absolute left-4 top-3 text-gray-500': !isFocused && !modelValue,
          }"
          class="transition-all duration-200">
          {{ placeholder }}
      </label>
      <input
          v-model="modelValue"
          :type="showPassword ? 'text' : type"
          @focus="isFocused = true"
          @blur="onBlur"
          :placeholder="!isFocused && !modelValue ? placeholder : ''"
          class="w-[280px] border-b-2 border-gray-400 py-2 px-4 focus:outline-none focus:border-primary transition duration-200"/> 
      <font-awesome-icon
          v-if="type === 'password'"
          :icon="showPassword ? 'eye-slash' : 'eye'"
          class="absolute right-2 top-4 cursor-pointer text-gray-500"
          @click="togglePasswordVisibility"
      />   
  </div>
</template>

<script setup>
import { ref, watch } from 'vue';

const props = defineProps({
  modelValue: {
      type: String,
      default: '',
  },
  type: {
      type: String,
      default: 'text',
  },
  placeholder: {
      type: String,
      default: '',
  },
});

const emit = defineEmits(['update:modelValue']);

const isFocused = ref(false);
const showPassword = ref(false); 

const togglePasswordVisibility = () => {
  showPassword.value = !showPassword.value;
};

const onBlur = () => {
  if (!modelValue) {
      isFocused.value = false;
  }
};

watch(() => props.modelValue, (newValue) => {
  modelValue.value = newValue;
});

const modelValue = ref(props.modelValue);
watch(modelValue, (newValue) => {
  emit('update:modelValue', newValue);
});
</script>

<style scoped>
input::placeholder {
  color: transparent;
}

label {
  pointer-events: none;
}
</style>
