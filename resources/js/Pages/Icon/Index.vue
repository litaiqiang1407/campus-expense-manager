<template>
    <div class="bg-white min-h-screen">
        <div class="grid grid-cols-5 gap-4 p-6">
            <!-- Display the list of icons from the API data -->
            <div v-for="(ic, index) in icon" :key="ic.id" class="h-full w-full rounded-full flex items-center justify-center">
                <img :src="ic.path" :alt="ic.name" class="object-cover" />
            </div>
        </div>
    </div>
        
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';

const icon = ref([]); // Variable to store icon data

// Function to call the API to fetch icon data
const fetchIcon = async () => {
    try {
        // Call the API to get data from the `Icon` route
        const response = await axios.get(route('Icon'));
        icon.value = response.data.icons; // Assign icon data from API to the variable
    } catch (error) {
        console.error('Error fetching icons:', error); // Catch and log errors if any occur
    } 
};

// Call the fetchIcon function when the component is mounted
onMounted(() => {
    fetchIcon();
});
</script>
