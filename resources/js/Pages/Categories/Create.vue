
<template>
  <div class="bg-white min-h-[calc(100vh-72px)]">
    <Form :action="'Save'" class="flex flex-col h-full">
      <div class="flex items-center gap-4">
        <Select :selectText="''" />
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
