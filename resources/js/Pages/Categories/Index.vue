<template>
    <div class="absolute w-screen h-screen bg-[#effbff]">
        <!-- Expense/Income/Debt Navigation -->
        <div class="max-w-lg mx-auto py-1">
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
        <Expense v-if="openComponent === 'expense'" />
        <Income v-if="openComponent === 'income'" />
        <DebtLoan v-if="openComponent === 'debtloan'" />
    </div>
</template>

<script setup>
import { ref } from 'vue';
import Expense from './Expense.vue';
import Income from './Income.vue';
import DebtLoan from './DebtLoan.vue';

const openComponent = ref('expense');

const displayComponent = (component) => {
    openComponent.value = component;
};
</script>