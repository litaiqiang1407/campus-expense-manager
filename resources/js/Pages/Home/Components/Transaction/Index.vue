<template>
    <div class="flex flex-col space-y-2">
        <div class="flex items-center justify-between">
            <span class="font-semibold text-[10px] text-secondaryText">Recent transaction</span>
            <button class="text-primary font-semibold text-[10px]" @click="goToTransactions">See all</button>
        </div>
        <div class="flex flex-col mt-2 p-4 rounded-lg shadow bg-white">
            <div v-if="isLoading" class="w-full h-32 flex items-center justify-center">
                <Loading class="size-8"/>
            </div>
            <div v-if="!isLoading && transactions.length === 0" class="flex flex-col items-center text-center py-8">
                <span class="font-semibold text-[10px] text-secondaryText">Top spending categories will show up here 🙌</span>
            </div>
            <div v-else-if="!isLoading && transactions.length > 0">
                <div v-for="(transaction, index) in transactions" :key="index" class="py-2">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-2">
                            <div class="relative">
                                <img :src="transaction?.category_icon_path || '/assets/icon/total.png'" class="size-8" alt="icon" />
                                <img :src="transaction?.wallet_icon_path || '/assets/icon/total.png'" class="rounded-full bottom-0 -right-0.5 size-3 absolute border-[2px] border-white" alt="icon" />
                            </div>
                            <div class="flex flex-col">
                                <span class="font-semibold text-[10px]">{{ transaction?.name || 'Transaction' }}</span>
                                <span class="text-[10px] text-secondaryText">{{ transaction?.date || "14 July 2004" }}</span>
                            </div>
                        </div>
                        <span class="font-semibold text-[10px]" 
                            :class="transaction?.type == 'expense' ? 'text-redText' : 'text-blueText'"
                            >
                                {{ formatBalance(transaction?.amount) || "99" }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { useRouter } from 'vue-router';
import { formatBalance } from '@/Helpers/Helpers';
import Loading  from '@/Components/Loading/Index.vue'; 

const router = useRouter();

const props = defineProps({
    transactions: Array,
    isLoading: Boolean
});

const goToTransactions = () => {
    router.push({ name: 'Transaction' });
}
</script>