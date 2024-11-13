<!-- <template>
  <div class="p-4 max-w-full overflow-hidden relative min-h-screen">
    <div class="space-y-4 mb-16">
      <div class="space-y-4 max-w-full">

        <div class="flex items-center">
          <font-awesome-icon icon="circle-question" class="text-secondaryText size-10 p-3"/> 
          <input type="text" placeholder="Category Name" class="ml-2 flex-1 border-b border-gray-400 focus:border-black outline-none text-xl font-medium placeholder-gray-400 bg-transparent" />
        </div>
    
          <div class="flex items-center ">
            <font-awesome-icon icon="plus-minus" class="text-black size-6 p-4 " />
            <span class=" pb-4 ml-4 flex-1 border-b border-gray-300 focus:border-black outline-none text-lg placeholder-gray-400">Expense</span>
          </div>
    
          <div class="flex items-center">
            <font-awesome-icon icon="layer-group" class="text-black size-6 p-4" />
            <div class="flex-1 pl-3">
              <span class="text-gray-500 font-extralight">Parent category</span>
              <p class="text-lg text-gray-400">Select category</p>
            </div>

            <button class="absolute right-0 pr-3">
              <font-awesome-icon icon="angle-right" class="text-secondaryText size-5" />
            </button>
        </div>
      </div>
    </div>

        <button class="w-11/12 py-4 bg-gray-200 text-gray-600 font-semibold rounded-full absolute bottom-2 left-0 right-0 mx-auto">
        Save
        </button>
  </div>
</template>
  
<script>
    
</script>
  
   -->

<template>
  <div class="bg-white min-h-[calc(100vh-72px)]">
    <Form :action="'Save'" class="flex flex-col h-full">
      <div class="flex items-center gap-4">
        <Select :selectText="''"/>
        <Input :placeholder="'Category Name'" />
      </div>
      <div class="flex items-center gap-7 my-3">
        <font-awesome-icon icon="plus-minus" class="text-black size-[16px]" />
        <span class="text-black py-4 border-t-[0.5px] border-b-[0.5px] w-full">Expense</span>
      </div>
      <div class="flex items-center gap-7">
        <font-awesome-icon icon="layer-group" class="text-black size-[16px]" />
        <div class="flex-1">
          <span class="text-secondaryText font-extralight text-[16px]">Parent category</span>
            <p class="text-[20px] text-gray-400">Select category</p>
        </div>
        <font-awesome-icon icon="angle-right" class="text-secondaryText size-5" />
      </div>
      <Submit class="mt-auto">Save</Submit>
    </Form>
  </div>
</template>

<script setup>
import { InputMoney, Select, Note, Form, Input } from '@/Components/Form/Index';
import Submit from '@/Components/Button/Submit/Index.vue';
</script>