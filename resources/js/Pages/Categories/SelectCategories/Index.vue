<template>
    <div class="bg-primaryBackground min-h-screen">
        <header class="sticky top-0 flex flex-col z-50 shadow mb-1">
            <div class="flex items-center w-full min-w-screen overflow-x-auto bg-white">
                <div class="w-1/2 flex-shrink-0 flex flex-col items-center" @click="selectCategory('expense')">
                    <span class="text-[12px] font-bold w-full text-center uppercase pt-2 px-4"
                        :class="{ 'text-black': activeCategory === 'expense', 'text-secondaryText': activeCategory !== 'expense' }">
                        Expense
                    </span>
                    <div class="h-[2px] w-full mt-2 rounded-t-full"
                        :class="{ 'bg-black': activeCategory === 'expense' }"></div>
                </div>
                <div class="w-1/2 flex-shrink-0 flex flex-col items-center pt-2 px-4" @click="selectCategory('income')">
                    <span class="text-[12px] font-bold w-full text-center uppercase"
                        :class="{ 'text-black': activeCategory === 'income', 'text-secondaryText': activeCategory !== 'income' }">
                        Income
                    </span>
                    <div class="h-[2px] w-full mt-2 rounded-t-full"
                        :class="{ 'bg-black': activeCategory === 'income' }"></div>
                </div>
            </div>
        </header>

        <div v-if="isLoading" class="w-full h-32 flex items-center justify-center">
            <Loading class="size-8" />
        </div>
        <div v-else-if="!isLoading && categories.length === 0" class="flex flex-col items-center text-center py-8">
            <span class="font-semibold text-[10px] text-secondaryText">No categories found 🙌</span>
        </div>
        <div v-else-if="!isLoading && categories.length > 0">
            <Expense v-if="activeCategory === 'expense'" :categories="expenseCategories" />
            <Income v-if="activeCategory === 'income'" :categories="incomeCategories" />
        </div>

        <!-- <div class="">
            <div v-for="category in getCategory()" :key="category.id" class="bg-white shadow my-2 py-2">
                <div class="flex justify-between items-center py-2 px-4" @click="goToEditTransaction(category)">
                    <div class="flex items-center space-x-3">
                        <img :src="category.icon_path" alt="Category Icon" class="w-10 h-10 rounded-full">
                        <div>
                            <p class="font-medium">{{ category.name }}</p>
                        </div>
                    </div>
                    <button class="absolute right-0 pr-3">
                        <font-awesome-icon icon="angle-right" class="text-secondaryText size-5" />
                    </button>
                </div>
                <ul v-if="getSubcategories(category.id).length" class="pl-8">
                    <li v-for="(subcategory, index) in getSubcategories(category.id)" :key="subcategory.id"
                        :class="['flex items-center space-x-2 py-2', index === getSubcategories(category.id).length - 1 ? 'border-left-half' : 'border-l-2']"
                        @click="goToEditTransaction(subcategory)">
                        <div class="h-[2px] bg-gray-200 w-2 absolute"></div>
                        <img :src="subcategory.icon_path" alt="Subcategory Icon" class="w-8 h-8 rounded-full">
                        <div>
                            <p class="font-medium">{{ subcategory.name }}</p>
                        </div>
                        <button class="absolute right-0 pr-3">
                            <font-awesome-icon icon="angle-right" class="text-secondaryText size-4" />
                        </button>
                    </li>
                </ul>
            </div>
        </div> -->
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import Expense from '../Expense.vue';
import Income from '../Income.vue';

import Loading from '@/Components/Loading/Index.vue';

const activeCategory = ref('expense');
const categories = ref([]);
const expenseCategories = ref([]);
const incomeCategories = ref([]);
const isLoading = ref(true);

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

// const goBack = () => {
//     window.history.back();
// };

onMounted(() => {
    fetchCategories();
});

// const props = defineProps({
//     categories: Array
// });

// const getCategory = () => {
//     if (!Array.isArray(props.categories)) {
//         return [];
//     }
//     return props.categories.filter(
//         category => category.parent_id === null && category.name !== "Expenses"
//     );
// };


// const getSubcategories = (parentId) => {
//     const subcategories = props.categories.filter(category => category.parent_id === parentId);
//     return subcategories;
// };
</script>

<style>
.border-left-half {
    position: relative;
}

.border-left-half::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 1.9px;
    height: 50%;
    background-color: #e5e7eb;
    z-index: 1;
}
</style>
