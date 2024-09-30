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

const props = defineProps({
    title: {
        type: String,
        default: ''
    },
    isBack: {
        type: Boolean,
        default: true
    },
    isCancel: {
        type: Boolean,
        default: false
    }
});

const goBack = () => {
    window.history.back()
};
</script>