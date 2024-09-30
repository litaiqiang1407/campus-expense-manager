<template>
    <component :is="layoutComponent">
      <router-view />
    </component>
  </template>

<script setup>
import { computed } from 'vue';
import { useRoute } from 'vue-router';
import NoneLayout from '@/Components/Layout/NoneLayout.vue';

const route = useRoute();
const layoutComponent = computed(() => {
    return route.meta.layout ? route.meta.layout : NoneLayout;
});
</script>