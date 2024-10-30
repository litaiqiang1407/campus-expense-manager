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
            </div>
        </div>

        <Expense v-if="openComponent === 'expense'" :categories="categories" />
        <Income v-if="openComponent === 'income'" :incomes="categories" /> 
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import Expense from './Expense.vue';
import Income from './Income.vue';
import axios from 'axios';

const openComponent = ref('expense');
const categories = ref([]);

const displayComponent = (component) => {
    openComponent.value = component;
    fetchCategories(component); 
};

const fetchCategories = async (categoryType) => {
    try {
        const response = await axios.get(route('Categories'), {
            params: { type: categoryType }
        });
        categories.value = response.data; 
        console.log(`Categories (type: ${categoryType}):`, categories.value); 
    } catch (error) {
        console.error('Error fetching categories:', error); 
    }
};

onMounted(() => {
    fetchCategories('expense'); 
});
</script>