<template>
    <component :is="layoutComponent" v-bind="layoutProps">
      <router-view />
    </component>
</template>

<script setup>
import { computed } from 'vue';
import { useRoute } from 'vue-router';
import NoneLayout from '@/Components/Layout/NoneLayout.vue';

const route = useRoute();
const layoutComponent = computed(() => {
    return route.meta.layout || NoneLayout;
});

const layoutProps = computed(() => {
  return { title: route.meta.title || 'Default Title' };
});
</script>