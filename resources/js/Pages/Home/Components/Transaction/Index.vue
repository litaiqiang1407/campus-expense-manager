<template>
    <div class="flex flex-col space-y-2">
        <div class="flex items-center justify-between">
            <span class="font-semibold text-[10px] text-secondaryText">Recent transaction</span>
            <button class="text-primary font-semibold text-[10px]">See all</button>
        </div>
            <div class="flex flex-col mt-2 p-4 rounded-lg shadow bg-white">
                <div v-for="(item, index) in transactionItems" :key="index" class="py-2">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-2">
                            <img :src="item.icon" class="w-8 h-8" alt="icon" />
                            <div class="flex flex-col">
                                <span class="font-semibold text-[10px]">{{ item.title }}</span>
                                <span class="text-[10px] text-secondaryText">$ {{ item.amount }}</span>
                            </div>
                        </div>
                        <span class="font-semibold text-[10px] text-redText">
                            {{ item.percentage }}%
                        </span>
                    </div>
                </div>
            </div>
    </div>
</template>

<script setup>
const transactionItems = [
    {
        id: 1,
        title: 'Food',
        icon: '/assets/icon/expense/food.png',
        amount: 2000,
        percentage: 50,
    },
    {
        id: 2,
        title: 'Rental',
        icon: '/assets/icon/expense/retails.png',
        amount: 1000,
        percentage: 25,
    },
    {
        id: 3,
        title: 'Shopping',
        icon: '/assets/icon/expense/shopping.png',
        amount: 500,
        percentage: 12.5,
    }
]
</script>