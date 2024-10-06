<template>
    <div class="bg-primaryBackground">
        <Header :title="title" :isBack="isBack">
            <component v-for="(comp, index) in headerComponent" :key="index" :is="comp" />
        </Header>
        <slot></slot>
        <BottomBar/>
    </div>
</template>

<script setup>
import Header from '@/Components/Header/Index.vue';
import BottomBar from '@/Components/BottomBar/Index.vue';

const props = defineProps({
    title: String,
    isBack: Boolean,
    isCancel: Boolean,
    headerComponent: Array 
});
</script>