<template>
    <div class="relative w-full">
        <label
        :class="{
          'transform -translate-y-6 left-0 text-[#00BC2A] text-sm': isFocused || inputValue,
          'absolute left-4 top-3 text-gray-500': !isFocused && !inputValue,
        }"
        class="transition-all duration-200"
      >
        {{ placeholder }}
      </label>
      <input
        v-model="inputValue"
        :type="type"
        @focus="isFocused = true"
        @blur="onBlur"
        :placeholder="!isFocused && !inputValue ? placeholder : ''"
        class="w-[280px] border-b-2 border-gray-400 py-2 px-4 focus:outline-none focus:border-[#00BC2A] transition duration-200"
      />    
    </div>
</template>

<script setup>
import { ref } from 'vue';

const props = defineProps({
  type: {
    type: String,
    default: 'text',
  },
  placeholder: {
    type: String,
    default: '',
  },
});

const inputValue = ref('');
const isFocused = ref(false);

const onBlur = () => {
  if (!inputValue.value) {
    isFocused.value = false;
  }
};
</script>

<style scoped>
input::placeholder {
  color: transparent;
}

label {
  pointer-events: none;
}
</style>
  