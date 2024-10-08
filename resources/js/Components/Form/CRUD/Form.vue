<template>
    <form class="mt-4 bg-white p-4" @submit.prevent="handleSubmit">
        <slot />
    </form>
</template>

<script setup>
import { defineEmits } from 'vue';

const props = defineProps({
    action: {
        type: String,
        required: true,
    },
});

const emit = defineEmits(['submit']);

const handleSubmit = () => {
    emit('submit');
};
</script>