<template>
    <div class="bg-primaryBackground pb-16">
        <slot></slot>
        <BottomBar/>
    </div>
</template>

<script setup>
import BottomBar from '@/Components/BottomBar/Index.vue';
</script>