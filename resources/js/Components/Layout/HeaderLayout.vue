<template>
    <div style="background-color: #EFFBFF;">
        <Header />
        <slot></slot>
    </div>
</template>

<script setup>
import Header from '@/Components/Header/Index.vue';
</script>