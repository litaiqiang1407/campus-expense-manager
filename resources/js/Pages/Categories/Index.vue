<template>
    <div class="absolute w-screen h-screen bg-[#effbff]">
        <!-- Expense/Income/Debt Navigation -->
        <div class="max-w-full mx-auto py-1 sticky top-0 z-50">
            <div class="flex justify-between text-xs font-medium bg-[#f8f9fa] px-4">
                <!-- EXPENSE Button -->
                <button
                    class="py-2 flex-1 mx-1"
                    :class="openComponent === 'expense' ? 'text-black border-b-2 border-black' : 'text-secondaryText'"
                    @click="displayComponent('expense')"
                >
                    EXPENSE
                </button>

                <!-- INCOME Button -->
                <button
                    class="py-2 flex-1 mx-1"
                    :class="openComponent === 'income' ? 'text-black border-b-2 border-black' : 'text-secondaryText'"
                    @click="displayComponent('income')"
                >
                    INCOME
                </button>

                <!-- DEBT/LOAN Button -->
                <button
                    class="py-2 flex-1 mx-1"
                    :class="openComponent === 'debtloan' ? 'text-black border-b-2 border-black' : 'text-secondaryText'"
                    @click="displayComponent('debtloan')"
                >
                    DEBT/LOAN
                </button>
            </div>
        </div>

        <!-- Component Display -->
        <Expense v-if="openComponent === 'expense'" :categories="categories" />
        <Income v-if="openComponent === 'income'" :incomes="categories" /> 
        <DebtLoan v-if="openComponent === 'debtloan'" :debts="filteredDebts" /> 
    </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue';
import Expense from './Expense.vue';
import Income from './Income.vue';
import DebtLoan from './DebtLoan.vue';
import axios from 'axios';


const openComponent = ref('expense');


const categories = ref([]);


const displayComponent = (component) => {
    openComponent.value = component;
    fetchCategories(component); n
};


const fetchCategories = async (componentType) => {
    try {
        const response = await axios.get(route('Categories')); 
        categories.value = response.data; 
        console.log('All Categories:', categories.value); 
    } catch (error) {
        console.error('Error fetching categories:', error); 
    }
};


const filteredDebts = computed(() => {
    return categories.value.filter(category => category.type === 'debt'); 
});


onMounted(() => {
    fetchCategories('expense'); 
});
</script>