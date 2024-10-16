<template>
    <header class="flex items-center justify-between px-4 py-2">
            <div class="flex flex-col">
                <div class="flex items-center font-bold">
                    <span class="text-[20px]">$ {{ totalBalance }}</span>
                    <font-awesome-icon icon="eye" class="ml-2 text-[16px]" />
                </div>
                <div class="flex items-center mt-1">
                    <span class="text-[8px] text-secondaryText">Total Balance</span>
                    <font-awesome-icon icon="circle-question" class="ml-2 text-[12px] text-[#999]" />
                </div>
            </div>
            <div class="flex items-center justify-center" @click="goToNotification">
                <font-awesome-icon icon="bell" class="text-[20px]" />
            </div>
        </header>
</template>

<script setup>
import { useRouter } from 'vue-router';

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