<template>
  <div class="p-4 max-w-full overflow-hidden relative min-h-screen">
    <div class="space-y-4 mb-16">
      <div class="space-y-4 max-w-full">
        <div class="flex items-center">
          <font-awesome-icon icon="circle-question" class="text-secondaryText size-10 p-3" @click="goToIconPage"/> 
          <input type="text" placeholder="Category Name" class="ml-2 flex-1 border-b border-gray-400 focus:border-black outline-none text-xl font-medium placeholder-gray-400 bg-transparent" />
        </div>
    
          <!-- Category Type Selector -->
          <div class="flex items-center ">
            <font-awesome-icon icon="plus-minus" class="text-black size-6 p-4 " />
            <span class=" pb-4 ml-4 flex-1 border-b border-gray-300 focus:border-black outline-none text-lg placeholder-gray-400">Expense</span>
          </div>
    
          <!-- Parent Category Selector -->
          <div class="flex items-center">
            <font-awesome-icon icon="layer-group" class="text-black size-6 p-4" />
            <div class="flex-1 pl-3">
              <span class="text-gray-500 font-extralight">Parent category</span>
              <p class="text-lg text-gray-400">Select category</p>
            </div>
            <!-- Right Arrow Icon -->
            <button class="absolute right-0 pr-3">
              <font-awesome-icon icon="angle-right" class="text-secondaryText size-5" />
            </button>
        </div>
      </div>
    </div>
        <!-- Save Button -->
        <button class="w-11/12 py-4 bg-gray-200 text-gray-600 font-semibold rounded-full absolute bottom-2 left-0 right-0 mx-auto">
        Save
        </button>
  </div>
</template>
  
<script setup>
import { useRouter } from 'vue-router';
const router = useRouter();
const goToIconPage = () => {
    router.push({ name: 'Icon' }); 
};
</script>
  