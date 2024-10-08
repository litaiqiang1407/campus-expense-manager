<template>
  <div class="w-full p-0 bg-blue-50 min-h-screen flex flex-col items-center">
    <!-- Header: Date Range -->
    <div class="w-full bg-white p-4 mb-4">
      <p class="text-black text-left text-sm font-bold ml-2">01/09/2024 - 31/10/2024</p>
      <div class="border-b-2 border-black w-2/6 ml-4 mt-0 relative top-4"></div>
    </div>

    <div class="bg-white p-4 rounded-none w-full max-w-full text-center mb-2 max-h-[400px] -mt-2">
      <div class="relative w-96 h-[260px] mx-auto mb-2 overflow-hidden">
        <!-- Background circle with SVG -->
        <svg class="w-full h-full" viewBox="0 0 100 50">
          <path id="arcPath" d="M 3,40 A 40,40 0 0,1 97,40" fill="none" stroke="#e5e7eb" stroke-width="2" stroke-linecap="round" />
          <circle id="greenCircle" cx="3" cy="40" r="2" fill="#00BC2A" />
          <circle id="whiteCircle" cx="3" cy="40" r="1" fill="white" />
        </svg>

        <!-- Budget amount in the center -->
        <div class="absolute inset-0 flex flex-col justify-center items-center">
          <p class="text-secondaryText text-xs mb-2">Amount you can spend</p>
          <p class="text-primary text-2xl font-bold tracking-normal">{{ budgetAvailable }}</p>
        </div>
      </div>

      <div class="flex justify-around text-gray-500 mb-2 w-full transform -translate-y-6">
        <div class="text-center">
          <p class="text-sm font-bold">{{ totalBudgets }}</p>
          <p class="text-xs mt-2">Total budgets</p>
        </div>
        <div class="w-px h-12 bg-primaryBackground"></div>
        <div class="text-center">
          <p class="text-sm font-bold">{{ totalSpent }}</p>
          <p class="text-xs mt-2">Total spent</p>
        </div>
        <div class="w-px h-12 bg-primaryBackground"></div>
        <div class="text-center">
          <p class="text-sm font-bold">{{ endOfPeriod }}</p>
          <p class="text-xs mt-2">End of period</p>
        </div>
      </div>

      <!-- Create Budget Button -->
      <button @click="createBudget" class="button-animate bg-primary text-white text-lg py-2 px-4 rounded-full shadow-lg w-2/4 mb-4 transform -translate-y-2">
        Create a Budget
      </button>
    </div>

    <div class="w-full max-w-full">
      <!-- First Budget Card (Not Overspent) -->
      <div class="bg-white rounded-lg p-4 mb-2 w-full">
        <div class="flex justify-between items-center mb-2">
          <div class="flex items-center">
            <img src="/assets/icon/expense/education.png" alt="Wallet Icon" class="rounded-full w-10 h-10" />
            <span class="ml-4 text-lg font-bold">Education</span>
          </div>
          <div class="text-right">
            <p class="font-bold text-lg">3.000</p>
            <p class="text-sm text-gray-500">Left 3.000</p>
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
      <div class="bg-white rounded-lg p-4 mb-2 w-full">
        <div class="flex justify-between items-center mb-2">
          <div class="flex items-center">
            <img src="/assets/icon/expense/food&drink.png" alt="Food Icon" class="rounded-full w-10 h-10" />
            <span class="ml-4 text-lg font-bold">Food & Drink</span>
          </div>
          <div class="text-right">
            <p class="font-bold text-lg">3.000</p>
            <p class="text-sm text-redText">Overspent 3.000</p> <!-- Changed color to redText -->
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

<script setup>
import { ref, onMounted } from 'vue';
import { useRouter } from 'vue-router';
const router = useRouter();

const createBudget = () => {
  router.push({ name: 'CreateBudget' });
};

// Define reactive variables
const budgetAvailable = ref('5.000');  // Available amount
const totalBudgets = ref('+500 K');     // Total budgets
const totalSpent = ref('0');            // Total spent
const endOfPeriod = ref('2 months');    // End of period

// Define event emitter for the component
const emit = defineEmits(['budget-created']);

// Define the method to create a budget
// function createBudget() {
//   emit('budget-created', budgetAvailable.value); // Emit event with the value of budgetAvailable
// }

// Animation function for moving the circles along the arc
function animateCircles() {
  const greenCircle = document.getElementById('greenCircle');
  const whiteCircle = document.getElementById('whiteCircle');
  const path = document.getElementById('arcPath');
  const pathLength = path.getTotalLength();
  let startTime = null;

  function animate(time) {
    if (!startTime) startTime = time;
    const progress = (time - startTime) / 3000; // Duration of 3 seconds
    const distance = progress * pathLength;

    if (progress < 1) {
      const point = path.getPointAtLength(distance);
      greenCircle.setAttribute('cx', point.x);
      greenCircle.setAttribute('cy', point.y);
      
      // Position the white circle relative to the green circle
      whiteCircle.setAttribute('cx', point.x);
      whiteCircle.setAttribute('cy', point.y);
      
      requestAnimationFrame(animate);
    } else {
      const endPoint = path.getPointAtLength(pathLength);
      greenCircle.setAttribute('cx', endPoint.x); // Final position
      greenCircle.setAttribute('cy', endPoint.y); // Final position
      whiteCircle.setAttribute('cx', endPoint.x);
      whiteCircle.setAttribute('cy', endPoint.y);
    }
  }

  requestAnimationFrame(animate);
}

// Start the animation on component mount
onMounted(() => {
  animateCircles();
});
</script>
