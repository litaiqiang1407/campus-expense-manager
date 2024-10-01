<template>
  <div class="w-full p-0 bg-blue-50 min-h-screen flex flex-col items-center">
    <!-- Header: Date Range -->
    <div class="w-full bg-white shadow-md p-4 mb-4">
      <p class="text-black text-left text-sm font-bold ml-2">01/09/2024 - 31/10/2024</p>
      <div class="border-b-2 border-black w-2/6 ml-4 mt-0 relative top-4"></div>
    </div>

    <div class="bg-white shadow-md p-4 rounded-none w-full max-w-full text-center mb-2" style="max-height: 420px;">
      <div class="relative w-96 h-96 mx-auto mb-2 overflow-hidden">
        <!-- Background circle -->
        <div class="absolute inset-0 rounded-full bg-white"></div>
        <div class="absolute inset-0 rounded-full border-8 border-gray-200"></div>

        <!-- Inner circle with transparent bottom -->
        <div class="absolute bottom-0 left-0 right-0 h-1/2 bg-white"></div>

        <!-- Small green dot in the top left corner of the inner circle -->
        <div class="absolute top-1/2 left-1.5 transform -translate-x-2 -translate-y-1/2 w-4 h-4 border-4 border-green-500 rounded-full bg-transparent"></div>

        <!-- Budget amount in the center -->
        <div class="absolute inset-0 flex flex-col justify-center items-center translate-y-[-10%]">
          <p class="text-gray-500 text-xs mb-2">Amount you can spend</p>
          <p class="text-green-500 text-3xl font-bold tracking-widest">5.000</p> <!-- Fixed display number -->
        </div>
      </div>

      <div class="flex justify-around text-gray-500 mb-2 w-full transform -translate-y-36">
        <div class="text-center">
          <p class="text-sm font-semibold">+500 K</p> <!-- Total budget -->
          <p class="text-xs mt-2">Total budgets</p>
        </div>
        <div class="text-center">
          <p class="text-sm font-semibold">0</p> <!-- Total amount spent -->
          <p class="text-xs mt-2">Total spent</p>
        </div>
        <div class="text-center">
          <p class="text-sm font-semibold">2 months</p> <!-- End of budget period -->
          <p class="text-xs mt-2">End of period</p>
        </div>
      </div>

      <!-- Create Budget Button -->
      <button class="bg-green-500 text-white text-lg py-2 px-4 rounded-full shadow-lg hover:bg-green-600 w-2/4 mb-4 transform -translate-y-28 transition-transform duration-200">
        Create a Budget
      </button>
    </div>

    <div class="w-full max-w-full">
      <!-- First Budget Card (Not Overspent) -->
      <div class="bg-white shadow-lg rounded-lg p-4 mb-2 w-full">
        <div class="flex justify-between items-center mb-2">
          <div class="flex items-center">
            <img src="/public/assets/icon/wallet.png" alt="Wallet Icon" class="rounded-full w-10 h-10" />
            <span class="ml-4 text-lg font-bold">Education</span>
          </div>
          <div class="text-right">
            <p class="font-bold text-lg">3.000</p>
            <p class="text-sm text-gray-500">Remaining 3.000</p>
          </div>
        </div>
        <!-- Progress Bar -->
        <div class="h-1.5 bg-green-100 rounded-full w-5/6 ml-auto">
          <div class="absolute h-1.5 border-l border-black left-1/2 transform -translate-x-1/2"></div>
          <div class="bg-green-500 h-full rounded-full w-[20%]"></div>
        </div>
        <!-- Today Indicator -->
        <div class="absolute left-1/2 transform -translate-x-1/2 h-4 border-l border-black top-2"></div>
        <div class="text-center mt-1">
          <span class="bg-white p-1 rounded border border-gray-200 text-black text-xs font-bold">Today</span>
          <div class="w-0 h-0 border-l-[5px] border-l-transparent border-r-[5px] border-r-transparent border-b-[5px] border-b-white mx-auto"></div>
        </div>
      </div>

      <!-- Second Budget Card (Overspent) -->
      <div class="bg-white shadow-lg rounded-lg p-4 mb-2 w-full">
        <div class="flex justify-between items-center mb-2">
          <div class="flex items-center">
            <img src="/public/assets/icon/food.png" alt="Food Icon" class="rounded-full w-10 h-10" />
            <span class="ml-4 text-lg font-bold">Food</span>
          </div>
          <div class="text-right">
            <p class="font-bold text-lg">3.000</p>
            <p class="text-sm text-red-600">Overspent 3.000</p>
          </div>
        </div>
        <!-- Progress Bar (Overspent) -->
        <div class="h-1.5 bg-red-100 rounded-full w-5/6 ml-auto">
          <div class="absolute h-1.5 border-l border-black left-1/2 transform -translate-x-1/2"></div>
          <div class="bg-red-500 h-full rounded-full w-full"></div>
        </div>
        <!-- Today Indicator -->
        <div class="absolute left-1/2 transform -translate-x-1/2 h-4 border-l border-black top-2"></div>
        <div class="text-center mt-1">
          <span class="bg-white p-1 rounded border border-gray-200 text-black text-xs font-bold">Today</span>
          <div class="w-0 h-0 border-l-[5px] border-l-transparent border-r-[5px] border-r-transparent border-b-[5px] border-b-white mx-auto"></div>
        </div>
      </div>
    </div>
  </div>
</template>

