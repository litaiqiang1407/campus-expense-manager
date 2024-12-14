<template>
    <div class="bg-primaryBackground min-h-screen">
        <header class="sticky top-0 flex flex-col z-50  shadow mb-1">
            <div class="flex items-center w-full min-w-screen overflow-x-auto bg-white">
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
        <div v-if="isLoading" class="w-full h-32 flex items-center justify-center">
            <Loading class="size-8"/>
        </div>
        <div v-else-if="!isLoading && categories.length === 0" class="flex flex-col items-center  text-center py-8">
            <span class="font-semibold text-[10px] text-secondaryText">No categories found 🙌</span>
        </div>
        <div v-else-if="!isLoading && categories.length > 0">
            <Expense v-if="activeCategory === 'expense'" :categories="expenseCategories" />
            <Income v-if="activeCategory === 'income'" :categories="incomeCategories" />
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import Expense from './Expense.vue';
import Income from './Income.vue';
import Loading from '@/Components/Loading/Index.vue';

const activeCategory = ref('expense');
const categories = ref([]);
const expenseCategories = ref([]);
const incomeCategories = ref([]);
const isLoading = ref(false);

const selectCategory = (category) => {
    activeCategory.value = category;
};

const fetchCategories = async () => {
    try {
        isLoading.value = true;
        const response = await axios.get(route('Categories'));
        categories.value = response.data;
        expenseCategories.value = response.data.filter(category => category.type === "expense");
        incomeCategories.value = response.data.filter(category => category.type === "income");
    } catch (error) {
        console.error('Error fetching categories:', error);
    } finally {
        isLoading.value = false;
    }
};

onMounted(() => {
    fetchCategories();
});
</script>
