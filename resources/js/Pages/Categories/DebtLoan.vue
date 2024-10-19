<template>
    <div class="absolute w-screen h-screen bg-[#effbff]">
        <!-- Vòng lặp qua các danh mục nợ -->
        <div v-for="debt in filteredDebts" :key="debt.id" class="bg-white shadow my-2">
            <div class="flex justify-between items-center pt-4 px-4">
                <div class="flex items-center space-x-3">
                    <img :src="debt.icon_path" alt="Debt Icon" class="w-10 h-10 rounded-full">
                    <div>
                        <p class="font-medium">{{ debt.name }}</p>
                        <p class="text-sm text-secondaryText">{{ debt.type }}</p>
                    </div>
                </div>
                <button class="absolute right-0 pr-3 z-50">
                    <font-awesome-icon icon="angle-right" class="text-secondaryText size-5" />
                </button>
            </div>

            <!-- Kiểm tra nếu danh mục cha có các danh mục con -->
            <ul class="pl-8" v-if="debt.subcategories && debt.subcategories.length">
                <li
                    v-for="(subcategory, index) in debt.subcategories"
                    :key="subcategory.id"
                    :class="[
                        'flex items-center space-x-2 py-2',
                        index === debt.subcategories.length - 1 ? 'border-left-half' : 'border-l-2' // Kiểm tra nếu đây là mục cuối cùng
                    ]"
                >
                    <div class="h-[2px] bg-gray-200 w-2 absolute"></div>
                    <img :src="subcategory.icon_path" alt="Subcategory Icon" class="w-8 h-8 rounded-full">
                    <div>
                        <p class="font-medium">{{ subcategory.name }}</p>
                        <p class="text-xs text-secondaryText">{{ subcategory.type }}</p>
                    </div>
                    <button class="absolute right-0 pr-3 z-50">
                        <font-awesome-icon icon="angle-right" class="text-secondaryText size-4" />
                    </button>
                </li>
            </ul>
        </div>

        <div class="flex justify-center items-center bg-white shadow my-2">
            <span class="text-primary font-semibold p-3">Need help? Send us a message?</span>
        </div>

        <div class="flex justify-left items-center bg-white shadow my-2">
            <button class="rounded-full px-4">
                <font-awesome-icon icon="plus" class="text-primary size-6" />
            </button>
            <span class="text-primary font-semibold p-3">Show inactive categories</span>
        </div>
    </div>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
    debts: {
        type: Array,
        required: true,
    },
});

const filteredDebts = computed(() => {
    if (!props.debts) {
        return []; 
    }
    return props.debts.filter(debt => debt.type === 'debt'); 
});

console.log('Filtered Debts:', filteredDebts.value);
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
