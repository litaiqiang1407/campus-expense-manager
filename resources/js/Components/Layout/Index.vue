<template>
    <div style="background-color: #EFFBFF;">
        <Header :title="title" :isBack="isBack"/>
        <slot></slot>
        <BottomBar/>
    </div>
</template>

<script setup>
import Header from '@/Components/Header/Index.vue';
import BottomBar from '@/Components/BottomBar/Index.vue';

const props = defineProps({
    title: String,
    isBack: Boolean
});
</script>