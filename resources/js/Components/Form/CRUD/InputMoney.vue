<template>
    <div class="flex w-full space-x-8 items-center py-4">
      <div class="w-[32px] flex items-center justify-center">
        <font-awesome-icon icon="dollar-sign" class="text-black text-[24px]" />
      </div>
      <input 
        type="text" 
        v-model="inputValue"
        class="w-full text-primary font-semibold text-[28px] border-b-[3px] border-primary py-1 focus:outline-none"
        @focus="showKeyboard = true"
      />
    </div>
  
    <!-- Bàn phím số -->
    <div v-if="showKeyboard" class="absolute bottom-0 left-0 w-full bg-gray-200 p-1">
      <div class="grid grid-cols-4 gap-1">
        <!-- Hàng 1: Clear, /, *, Delete -->
        <button type="button" @click="clearInput" class="button-animate bg-slate-50 p-4 rounded text-xl text-primary font-bold">C</button>
        <button type="button" @click="addOperator('/')" class="button-animate bg-slate-50 p-4 rounded text-xl text-primary">
            <font-awesome-icon icon="divide" />
        </button>
        <button type="button" @click="addOperator('*')" class="button-animate bg-slate-50 p-4 rounded text-xl text-primary">
            <font-awesome-icon icon="xmark" />
        </button>
        <button type="button" @click="deleteNumber" class="button-animate bg-slate-50 p-4 rounded text-xl text-primary">
            <font-awesome-icon icon="delete-left" />
        </button>
  
        <!-- Hàng 2: 7, 8, 9, - -->
        <button type="button" @click="addNumber(7)" class="button-animate bg-white p-4 rounded text-xl text-primary ">7</button>
        <button type="button" @click="addNumber(8)" class="button-animate bg-white p-4 rounded text-xl text-primary ">8</button>
        <button type="button" @click="addNumber(9)" class="button-animate bg-white p-4 rounded text-xl text-primary ">9</button>
        <button type="button" @click="addOperator('-')" class="button-animate bg-slate-50 p-4 rounded text-xl text-primary">
            <font-awesome-icon icon="minus" />
        </button>
  
        <!-- Hàng 3: 4, 5, 6, + -->
        <button type="button" @click="addNumber(4)" class="button-animate bg-white p-4 rounded text-xl text-primary ">4</button>
        <button type="button" @click="addNumber(5)" class="button-animate bg-white p-4 rounded text-xl text-primary ">5</button>
        <button type="button" @click="addNumber(6)" class="button-animate bg-white p-4 rounded text-xl text-primary ">6</button>
        <button type="button" @click="addOperator('+')" class="button-animate bg-slate-50 p-4 rounded text-xl text-primary">
            <font-awesome-icon icon="plus" />
        </button>

        <!-- Hàng 4: 1, 2, 3, Hide Keyboard -->
        <button type="button" @click="addNumber(1)" class="button-animate bg-white p-4 rounded text-xl text-primary ">1</button>
        <button type="button" @click="addNumber(2)" class="button-animate bg-white p-4 rounded text-xl text-primary ">2</button>
        <button type="button" @click="addNumber(3)" class="button-animate bg-white p-4 rounded text-xl text-primary ">3</button>
        <button type="button" @click="hideKeyboard" class="button-animate flex items-center justify-center p-4 rounded text-xl bg-primary row-span-2">
          <font-awesome-icon icon="play" class="text-white size-7" />
        </button>

        <!-- Hàng 5: 0, 000, ., Hide Keyboard -->
        <button type="button" @click="addNumber(0)" class="button-animate bg-white p-4 rounded text-xl text-primary ">0</button>
        <button type="button" @click="addNumber('000')" class="button-animate bg-white p-4 rounded text-xl text-primary ">000</button>
        <button type="button" @click="addDecimal" class="button-animate bg-white p-4 rounded text-xl text-primary ">.</button>
      </div>   
    </div>
</template>
  
<script setup>
import { ref } from 'vue';

const inputValue = ref(0);
const showKeyboard = ref(false);

const addNumber = (num) => {
  inputValue.value += num;
};

const addDecimal = () => {
  if (!inputValue.value.includes('.')) {
    inputValue.value += '.';
  }
};

const addOperator = (operator) => {
  inputValue.value += operator;
};

const deleteNumber = () => {
  inputValue.value = inputValue.value.slice(0, -1);
};

const clearInput = () => {
  inputValue.value = '';
};

const hideKeyboard = () => {
  showKeyboard.value = false;
};
</script>

