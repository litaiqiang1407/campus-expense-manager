<template>
    <form class="mt-4 bg-white p-4">
        <slot />
    </form>
    <div class="w-full p-2">
        <Submit>{{ action }}</Submit>
    </div>
</template>

<script setup>
import { Submit } from '@/Components/Button';
const props = defineProps({
    action: {
        type: String,
        required: true,
    },
});
</script>