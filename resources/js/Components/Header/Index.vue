<template>
    <header class="sticky h-13 flex items-center justify-between px-4 py-2 bg-white shadow z-50">
        <div class="flex items-center space-x-4">
            <font-awesome-icon v-if="isBack" icon="arrow-left" class="text-[24px]" @click="goBack" />
            <font-awesome-icon v-if="isCancel" icon="xmark" class="text-[24px]" @click="goBack" />
            <h1 class="text-[20px] font-bold">{{ title }}</h1>
        </div>
        <div class="flex items-center space-x-4">
            <slot></slot>
        </div>
    </header>
</template>
<script setup>
import { computed } from 'vue';
import { useRoute } from 'vue-router';

const route = useRoute();

const title = computed(() => route.meta.title || '');
const isBack = computed(() => route.meta.isBack !== false);  
const isCancel = computed(() => route.meta.isCancel === true); 

const goBack = () => {
    window.history.back()
};
</script>
