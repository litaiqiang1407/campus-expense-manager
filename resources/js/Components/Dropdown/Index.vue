<template>
    <div class="flex w-full space-x-8 items-center py-4">
      <div class="size-[40px] flex items-center justify-center">
        <font-awesome-icon :icon="icon" class="text-black text-[36px]" />
      </div>
      <div class="relative w-full">
        <button type="button"
          class="button-animate flex items-center justify-between w-full border-[1px] font-semibold border-primary px-4 py-2 rounded-md shadow-sm text-[20px] focus:outline-none"
          @click="toggleDropdown"
        >
          <span>{{ selectedItem ? selectedItem.name : defaultText }}</span>
          <font-awesome-icon :icon="isOpen ? 'chevron-up' : 'chevron-down'" class="text-primary" />
        </button>
  
        <div v-if="isOpen" class="absolute mt-2 w-full bg-white font-semibold shadow-lg border-2 rounded-md z-10">
          <ul class="max-h-48 overflow-auto">
            <li
              v-for="item in itemList"
              :key="item.id"
              @click="selectItem(item)"
              class="button-animate cursor-pointer px-4 py-2 hover:bg-gray-100 text-[16px] "
            >
              {{ item.name }}
            </li>
          </ul>
          </div>
      </div>
    </div>
</template>

<script setup>
import { ref, watch, defineEmits } from 'vue';

const props = defineProps({
    itemList: {
        type: Array,
        default: [],
    },
    modelValue: {
        type: Object,
        default: null,
    },
    defaultText: {
        type: String,
        default: 'Select',
    },
    icon: {
        type: String,
        default: 'circle-question',
    },
});

const emit = defineEmits(['update:modelValue']);

const isOpen = ref(false);
const selectedItem = ref(props.modelValue);

const toggleDropdown = () => {
    isOpen.value = !isOpen.value;
};

const selectItem = (item) => {
    selectedItem.value = item;
    emit('update:modelValue', item);
    isOpen.value = false;
};

watch(() => props.modelValue, (newVal) => {
    selectedItem.value = newVal;
});
</script>
