<template>
    <header class="flex flex-col justify-between px-4 py-2">
        <div class="flex items-center w-full">
            <div class="flex items-center justify-star font-bold w-full">
                <span class="text-[20px]">{{ formatBalance(totalBalance) }}</span>
                <font-awesome-icon icon="eye" class="ml-2 text-[14px]" />
            </div>
            <div class="flex items-center justify-end w-full" @click="goToNotification">
                <font-awesome-icon icon="bell" class="text-[16px]" />
            </div>
        </div>
        <div class="flex items-center mt-1">
            <span class="text-[10px] text-secondaryText">Total Balance</span>
            <font-awesome-icon icon="circle-question" class="ml-2 text-[12px] text-[#999]" />
        </div>           
    </header>
</template>

<script setup>
import { useRouter } from 'vue-router';
import { formatBalance } from '@/Helpers/Helpers';

const router = useRouter();

const props = defineProps({
    totalBalance: {
        type: Number,
        default: 0,
    },
});

const goToNotification = () => {
    router.push({ name: 'Notification' });
};
</script>