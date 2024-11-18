<template>
    <div class="py-4">
        <button
            type="button"
            class="flex w-full space-x-2 items-center"
            @click="goToNote"
            :disabled="readonly.value"
        >
            <div class="size-[20px] flex items-center justify-center">
                <font-awesome-icon icon="fa-regular fa-comment" class="text-black text-[16px]" />
            </div>
            <input
                v-if="noteContent"
                v-model="noteContent"
                placeholder="Write Note"
                class="text-secondaryText text-[14px] w-full outline-none leading-none cursor-default" />
            <span v-else class="font-medium text-[14px] text-secondaryText">Write note</span>
        </button>
    </div>
</template>

<script setup>
import { ref, defineProps, watch, defineEmits, onMounted, readonly } from 'vue';
import { useRouter } from 'vue-router';

const props = defineProps({
    modelValue: {
        type: String,
        default: ''
    },
    fromPage: {
        type: String,
        default: 'EditTransaction'
    },
    readonly: {
        type: Boolean,
        default: false
    }
});

const emit = defineEmits(['update:modelValue']);
const noteContent = ref(props.modelValue);
const router = useRouter();

onMounted(() => {
    noteContent.value = router.currentRoute.value.query.value || props.modelValue;
});

watch(noteContent, (newValue) => {
    emit('update:modelValue', newValue);
});

watch(() => props.modelValue, (newValue) => {
    noteContent.value = newValue;
});

// Hàm goToNote chỉ chạy khi readonly là false
const goToNote = () => {
    if (props.readonly) return;  // Nếu readonly là true thì không thực hiện gì cả

    const transactionId = router.currentRoute.value.params.transactionId;
    router.push({
        name: 'Note',
        query: {
            transactionId: transactionId,
            value: noteContent.value,
            fromPage: props.fromPage
        }
    });
};
</script>
