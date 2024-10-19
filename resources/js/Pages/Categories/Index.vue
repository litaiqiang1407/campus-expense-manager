<template>
    <div class="absolute w-screen h-screen bg-[#effbff]">
        <!-- Expense/Income/Debt Navigation -->
        <div class="max-w-lg mx-auto py-1 sticky top-0 z-50">
            <div class="flex justify-around text-xs font-medium bg-[#f8f9fa] ">
                <!-- EXPENSE Button -->
                <button
                    class="py-2 px-4"
                    :class="openComponent === 'expense' ? 'text-black border-b-2 border-black' : 'text-secondaryText'"
                    @click="displayComponent('expense')"
                >
                    EXPENSE
                </button>

                <!-- INCOME Button -->
                <button
                    class="py-2 px-4"
                    :class="openComponent === 'income' ? 'text-black border-b-2 border-black' : 'text-secondaryText'"
                    @click="displayComponent('income')"
                >
                    INCOME
                </button>

                <!-- DEBT/LOAN Button -->
                <button
                    class="py-2 px-4"
                    :class="openComponent === 'debtloan' ? 'text-black border-b-2 border-black' : 'text-secondaryText'"
                    @click="displayComponent('debtloan')"
                >
                    DEBT/LOAN
                </button>
            </div>
        </div>

        <!-- Component Display -->
        <Expense v-if="openComponent === 'expense'" :categories="categories" />
        <Income v-if="openComponent === 'income'" :incomes="categories" /> <!-- Truyền incomes vào đây -->
        <DebtLoan v-if="openComponent === 'debtloan'" :debts="filteredDebts" /> <!-- Truyền dữ liệu nợ vào đây -->
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

// Hàm hiển thị component tương ứng
const displayComponent = (component) => {
    openComponent.value = component;
    fetchCategories(component); // Gọi lại fetchCategories với component được chọn
};

// Hàm lấy danh mục
const fetchCategories = async (componentType) => {
    try {
        const response = await axios.get(route('Categories')); 
        categories.value = response.data; // Lưu tất cả danh mục
        console.log('All Categories:', categories.value); // Kiểm tra dữ liệu
    } catch (error) {
        console.error('Error fetching categories:', error);
    }
};

// Hàm lọc danh sách nợ
const filteredDebts = computed(() => {
    return categories.value.filter(category => category.type === 'debt'); // Lọc danh sách nợ từ categories
});

// Gọi hàm khi component được mount
onMounted(() => {
    fetchCategories('expense'); // Mặc định là expense
});
</script>