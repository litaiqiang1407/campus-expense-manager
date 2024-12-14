<template>
    <header class="sticky h-13 flex items-center justify-between px-4 py-2 bg-white shadow z-50">
        <div class="flex items-center space-x-4">
            <font-awesome-icon v-if="isBack" icon="arrow-left" class="text-[20px]" @click="goBack" />
            <font-awesome-icon v-if="isCancel" icon="xmark" class="text-[20px]" @click="goCancel" />
            <h1 class="text-[20px] font-semibold">{{ title }}</h1>
        </div>
        <div class="flex items-center space-x-4">
            <slot></slot>
        </div>
    </header>
</template>
<script setup>
import { computed } from 'vue';
import { useRouter, useRoute } from 'vue-router';
import { goBack as helperGoBack, goCancel as helperGoCancel } from '@/Helpers/Helpers';

const router = useRouter();
const route = useRoute();

const title = computed(() => route.meta.title || '');
const isBack = computed(() => route.meta.isBack !== false);  
const isCancel = computed(() => route.meta.isCancel === true); 

const goBack= () => helperGoBack(router);  
const goCancel= () => helperGoCancel(router); 
</script>
