<template>
    <button type="button" class="flex w-full space-x-8 items-center py-4">
        <div class="w-[32px] flex items-center justify-center">
            <font-awesome-icon :icon="icon" class="text-black text-[24px]" />
        </div>
        <span class="text-secondaryText font-medium" :style="{ fontSize: sizeText + 'px' }">{{ selectText }}</span>
    </button>
</template>
<script setup>
const props = defineProps({
    selectText: {
        type: String,
        default: 'Select',
    },
    icon: {
        type: String,
        default: 'circle-question',
    },
    sizeText: {
        type: String,
        default: '14',
    },
});
</script>