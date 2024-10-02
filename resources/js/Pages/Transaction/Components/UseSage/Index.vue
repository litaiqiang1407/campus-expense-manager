<template>
    <div class="m-0 mt-2 p-0 bg-white">
        <div class="flex items-center justify-between px-4 py-2 relative">
            <div class="flex items-start space-x-2">
                <div class="flex flex-row items-start">
                    <h1 class="text-3xl">{{ dayUse }}</h1>
                    <div class="flex flex-col items-start pt-1 pl-1">
                        <p class="text-10px">Today</p>
                        <p class="text-10px">September</p>
                    </div>
                </div>
            </div>
            <p class="text-sm">{{ totalFlow }}</p>
            <span class="absolute left-0 bottom-0 w-full h-0.5 bg-black"></span>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';

const dayUse = '14';
const totalFlow = '22';
</script>
