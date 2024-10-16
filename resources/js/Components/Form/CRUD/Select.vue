<template>
    <div>
        <button type="button" class="flex w-full space-x-8 items-center">
            <div class="size-[60px] flex items-center justify-center">
                <img v-if="iconSrc" :src="iconSrc" class="size-[44px]" />
                <font-awesome-icon v-if="!iconSrc" :icon="icon" class="text-black text-[36px]" />
            </div>
            <span class="font-medium" :class="iconSrc ? 'hidden' : 'text-secondaryText'" :style="{ fontSize: sizeText + 'px' }">{{ selectText }}</span>
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