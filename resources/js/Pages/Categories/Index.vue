<template>
    <div class="bg-primaryBackground min-h-screen">
        <header class="sticky top-0 flex flex-col z-50">
            <div class="h-13 flex items-center justify-between px-4 py-2 bg-white ">
                <div class="flex items-center gap-4">
                    <font-awesome-icon icon="arrow-left" class="text-[24px]" @click="goBack" />
                    <h1 class="text-[20px] font-bold">Budgets</h1>
                </div>
                <div class="flex items-center space-x-4">
                    <button >
                        <img src="/assets/img/earth.png" alt="Wallet" class="size-8">
                    </button>
                    <button class="flex items-center">
                        <font-awesome-icon icon="ellipsis-vertical" class="text-[24px]" />
                    </button>
                </div>
            </div>
            <div class="flex items-center w-full min-w-screen overflow-x-auto bg-white mb-1">
                <div class="flex items-center w-full min-w-screen overflow-x-auto bg-white">
                    <div class="w-1/2 flex-shrink-0 flex flex-col items-center" 
                        @click="selectCategory('expense')">
                        <span class="text-[12px] font-bold w-full text-center uppercase pt-2 px-4"
                        :class="{ 'text-black': activeCategory === 'expense', 'text-secondaryText': activeCategory !== 'expense' }">
                            Expense
                        </span>
                        <div class="h-[2px] w-full mt-2 rounded-t-full"
                        :class="{ 'bg-black': activeCategory === 'expense' }"></div>
                    </div>
                    <div class="w-1/2 flex-shrink-0 flex flex-col items-center pt-2 px-4" 
                        @click="selectCategory('income')">
                        <span class="text-[12px] font-bold w-full text-center uppercase"
                        :class="{ 'text-black': activeCategory === 'income', 'text-secondaryText': activeCategory !== 'income' }">
                        Income
                        </span>
                        <div class="h-[2px] w-full mt-2 rounded-t-full"
                        :class="{ 'bg-black': activeCategory === 'income' }"></div>
                    </div>
                </div>
            </div>
        </header>
        <Expense v-if="activeCategory === 'expense'" :categories="expenseCategories" />
        <Income v-if="activeCategory === 'income'" :categories="incomeCategories" /> 
    </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue';
import Expense from './Expense.vue';
import Income from './Income.vue';
import axios from 'axios';

const activeCategory = ref('expense');
const categories = ref([]);
const expenseCategories = ref([]);
const incomeCategories = ref([]);

const selectCategory = (category) => {
    activeCategory.value = category; 
};

const fetchCategories = async () => {
    try {
        const response = await axios.get(route('Categories'));
        categories.value = response.data; 
        expenseCategories.value = response.data.filter(category => category.type === "expense");
        incomeCategories.value = response.data.filter(category => category.type === "income");
    } catch (error) {
        console.error('Error fetching categories:', error); 
    }
};

const goBack = () => {
    window.history.back()
};

onMounted(() => {
    fetchCategories(); 
});
</script>