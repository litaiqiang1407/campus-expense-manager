<template>
    <div class="bg-primaryBackground">
        <Header :title="title" />
        <slot></slot>
    </div>
</template>

<script setup>
import Header from '@/Components/Header/Index.vue';

const props = defineProps({
    title: String
});
</script>