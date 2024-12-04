<template>
    <div class="flex w-full space-x-2 items-center py-4">
      <div class="size-[20px] flex items-center justify-center">
        <font-awesome-icon icon="dollar-sign" class="text-black text-[16px]" />
      </div>
      <input
        type="text"
        :value="inputValue"
        class="w-full text-primary font-semibold text-[16px] border-b-[3px] border-primary py-1 focus:outline-none"
        :readonly="readonly"
        @focus="!readonly && (showKeyboard = true)"
      />
    </div>

    <!-- Bàn phím số -->
    <div v-if="!readonly && showKeyboard" class="absolute bottom-0 left-0 w-full bg-gray-200 p-1">
      <div class="grid grid-cols-4 gap-1">
        <!-- Hàng 1: Clear, /, *, Delete -->
        <button type="button" @click="clearInput" class="button-animate bg-slate-50 px-4 py-2 text-[16px] text-primary font-bold">C</button>
        <button type="button" @click="addOperator('/')" class="button-animate bg-slate-50 px-4 py-2 text-[16px] text-primary">
          <font-awesome-icon icon="divide" />
        </button>
        <button type="button" @click="addOperator('*')" class="button-animate bg-slate-50 px-4 py-2 text-[16px] text-primary">
          <font-awesome-icon icon="xmark" />
        </button>
        <button type="button" @click="deleteNumber" class="button-animate bg-slate-50 px-4 py-2 text-[16px] text-primary">
          <font-awesome-icon icon="delete-left" />
        </button>

        <!-- Hàng 2: 7, 8, 9, - -->
        <button type="button" @click="addNumber(7)" class="button-animate bg-white px-4 py-2 text-[16px] text-primary ">7</button>
        <button type="button" @click="addNumber(8)" class="button-animate bg-white px-4 py-2 text-[16px] text-primary ">8</button>
        <button type="button" @click="addNumber(9)" class="button-animate bg-white px-4 py-2 text-[16px] text-primary ">9</button>
        <button type="button" @click="addOperator('-')" class="button-animate bg-slate-50 px-4 py-2 text-[16px] text-primary">
          <font-awesome-icon icon="minus" />
        </button>

        <!-- Hàng 3: 4, 5, 6, + -->
        <button type="button" @click="addNumber(4)" class="button-animate bg-white px-4 py-2 text-[16px] text-primary ">4</button>
        <button type="button" @click="addNumber(5)" class="button-animate bg-white px-4 py-2 text-[16px] text-primary ">5</button>
        <button type="button" @click="addNumber(6)" class="button-animate bg-white px-4 py-2 text-[16px] text-primary ">6</button>
        <button type="button" @click="addOperator('+')" class="button-animate bg-slate-50 px-4 py-2 text-[16px] text-primary">
          <font-awesome-icon icon="plus" />
        </button>

        <!-- Hàng 4: 1, 2, 3, Hide Keyboard -->
        <button type="button" @click="addNumber(1)" class="button-animate bg-white px-4 py-2 text-[16px] text-primary ">1</button>
        <button type="button" @click="addNumber(2)" class="button-animate bg-white px-4 py-2 text-[16px] text-primary ">2</button>
        <button type="button" @click="addNumber(3)" class="button-animate bg-white px-4 py-2 text-[16px] text-primary ">3</button>
        <button type="button" @click="hideKeyboard" class="button-animate flex items-center justify-center px-4 py-2 text-[16px] bg-primary row-span-2">
          <font-awesome-icon icon="play" class="text-white size-7" />
        </button>

        <!-- Hàng 5: 0, 000, ., Hide Keyboard -->
        <button type="button" @click="addNumber(0)" class="button-animate bg-white px-4 py-2 text-[16px] text-primary ">0</button>
        <button type="button" @click="addNumber('000')" class="button-animate bg-white px-4 py-2 text-[16px] text-primary ">000</button>
        <button type="button" @click="addDecimal" class="button-animate bg-white px-4 py-2 text-[16px] text-primary ">.</button>
      </div>
    </div>
  </template>

  <script setup>
  import { ref, defineEmits, watch } from 'vue';
  import { evaluate } from 'mathjs';

  const emit = defineEmits(['update:inputValue']);

  const props = defineProps({
    inputValue: {
      type: String,
      default: '0',
    },
    readonly: {
      type: Boolean,
      default: false,
    },
  });

  const showKeyboard = ref(false);

  const addNumber = (num) => {
    if (props.readonly) return;
    if (props.inputValue === '0') {
      emit('update:inputValue', num.toString());
    } else {
      emit('update:inputValue', props.inputValue + num);
    }
  };

  const addDecimal = () => {
    if (props.readonly) return;
    if (!props.inputValue.toString().includes('.')) {
      emit('update:inputValue', props.inputValue + '.');
    }
  };

  const addOperator = (operator) => {
    if (props.readonly) return;
    emit('update:inputValue', props.inputValue + operator);
  };

  const deleteNumber = () => {
    if (props.readonly) return;
    emit('update:inputValue', props.inputValue.toString().slice(0, -1));
  };

  const clearInput = () => {
    if (props.readonly) return;
    emit('update:inputValue', '');
  };

  const evaluateExpression = () => {
    if (props.readonly) return;
    try {
      const result = evaluate(props.inputValue);
      emit('update:inputValue', result.toString());
    } catch (e) {
      console.error('Error in expression:', e);
    }
  };

  const hideKeyboard = () => {
    if (props.readonly) return;
    if (/[+\-*/]/.test(props.inputValue)) {
      evaluateExpression();
    } else {
      showKeyboard.value = false;
    }
  };

  watch(() => props.inputValue, (newValue) => {
    localStorage.setItem('amount', newValue);
  });
  </script>
