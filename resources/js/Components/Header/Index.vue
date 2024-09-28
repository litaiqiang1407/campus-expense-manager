<template>
    <header class="h-13 flex items-center px-4 py-2">
        <div class="flex items-center space-x-4">
            <font-awesome-icon icon="arrow-left" class="text-[24px]" @click="goBack" />
            <h1 class="text-[20px] font-bold">{{ title }}</h1>
        </div>
        <div class="flex items-center space-x-4">
            <slot></slot>
        </div>
    </header>
</template>

<script setup>

const props = defineProps({
    title: String
});

const goBack = () => {
    window.history.back()
};
</script>