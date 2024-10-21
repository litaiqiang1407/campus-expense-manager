<template>
    <div class="relative">
        <button
            type="button"
            class="flex w-full space-x-8 items-center py-4"
            @click="selectWallet"
        >
            <div class="size-[40px] flex items-center justify-center">
                <img v-if="iconSrc" :src="iconSrc" class="size-[36px]" />
                <font-awesome-icon v-if="!iconSrc" :icon="icon" class="text-black text-[36px]" />
            </div>
            <span class="text-secondaryText font-medium" :style="{ fontSize: sizeText + 'px' }">{{ selectText }}</span>
        </button>

        <!-- Dropdown List -->
        <div
            v-if="isDropdownOpen"
            class="absolute z-10 w-full bg-white border border-gray-300 rounded-md mt-2"
        >
            <ul class="max-h-60 overflow-y-auto">
                <li
                    v-for="(item, index) in items"
                    :key="index"
                    @click="selectItem(item)"
                    class="py-2 px-4 hover:bg-gray-200 cursor-pointer"
                >
                    {{ getItemLabel(item) }}
                </li>
            </ul>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';

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
    items: {
        type: Array,
        default: () => [],
    },
    getItemLabel: {
        type: Function,
        default: (item) => item.name || item,
    },
});

const emit = defineEmits(['update:selectText']);
const isDropdownOpen = ref(false);
const router = useRouter();

const selectWallet = () => {
    router.push({ name: 'SelectWallet' });
};

const selectItem = (item) => {
    emit('update:selectText', item);
    isDropdownOpen.value = false;
};
</script>
