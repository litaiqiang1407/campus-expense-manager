<template>
    <div class="bg-white min-h-screen">
        <div class="grid grid-cols-5 gap-4 p-6">
            <div v-for="(ic, index) in icon" :key="index" class="h-full w-full rounded-full flex items-center justify-center">
                <img :src="ic.path" :alt="ic.name" class="object-cover" />
            </div>
        </div>
    </div>
        
</template>

<script setup>
import { ref, onMounted } from 'vue';

const icon = ref([]); 

const fetchIcon = async () => {
    try {
        const response = await axios.get(route('Icon'));
        icon.value = response.data.icons; 
    } catch (error) {
        console.error('Error fetching icons:', error); 
    } 
};

onMounted(() => {
    fetchIcon();
});
</script>
