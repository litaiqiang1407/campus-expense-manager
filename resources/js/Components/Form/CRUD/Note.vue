<template>
    <div class="py-4">
        <button type="button" class="flex w-full space-x-2 items-center" @click="goToNote">
            <div class="size-[20px] flex items-center justify-center">
                <font-awesome-icon icon="fa-regular fa-comment" class="text-black text-[16px]" />
            </div>
            <input
                v-if="noteContent"
                v-model="noteContent"
                placeholder="Write Note"
                class="text-secondaryText text-[14px] w-full outline-none leading-none">
            </input>
            <span v-else class="font-medium text-[14px] text-secondaryText">Write note</span>
        </button>
    </div>
</template>

<script setup>
import { ref, defineProps, watch, defineEmits } from 'vue';
import { useRouter, useRoute } from 'vue-router';

const props = defineProps({
    modelValue: {
        type: String,
        default: ''
    }
});

const emit = defineEmits(['update:modelValue']);
const noteContent = ref(props.modelValue);
const router = useRouter();

watch(noteContent, (newValue) => {
    emit('update:modelValue', newValue);
});

watch(() => props.modelValue, (newValue) => {
    noteContent.value = newValue;
});

const goToNote = () => {
    const transactionId = router.currentRoute.value.params.transactionId;
    if (transactionId) {
        router.push({
        name: 'Note',
        query: {
            transactionId : transactionId,
            fromPage: 'EditTransaction'
        }
    });
    } else {
        console.error("transactionId is missing in route params.");
    }
};
</script>
