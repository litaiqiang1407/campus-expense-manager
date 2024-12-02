<template>
    <div class="py-2">
        <button type="button" class="flex w-full space-x-2 items-center">
            <div v-if="iconSrc" class="size-[60px] flex items-center justify-start">
                <img :src="iconSrc" class="size-[44px]" />
            </div>
            <div v-else class="size-[20px] flex items-center justify-center">
                <font-awesome-icon :icon="icon" class="text-black text-[16px]" />
            </div>
            <span class="font-medium text-secondaryText">{{ selectText }}</span>
        </button>
    </div>
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
    iconSrc: {
        type: String,
        default: '',
    },
});
</script>
