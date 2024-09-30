<template>
    <div style="background-color: #EFFBFF;">
        <slot></slot>
        <BottomBar/>
    </div>
</template>

<script setup>
import BottomBar from '@/Components/BottomBar/Index.vue';
</script>