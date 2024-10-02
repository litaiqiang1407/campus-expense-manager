<template>
    <div>
        <NoData :message="'No notifications'" />
    </div>
</template>

<script setup>
import NoData from '@/Components/NoData/Index.vue';
import { ref, onMounted } from 'vue';
import axios from 'axios';

const notifications = ref([]); // Reactive variable to hold notifications

// Function to fetch notifications
const fetchNotifications = async () => {
    try {
        const response = await axios.get('/notification'); // Adjust the URL as necessary
        notifications.value = response.data; // Set the fetched notifications
        console.log('Notifications:', notifications.value);
    } catch (error) {
        console.error('Error fetching notifications:', error);
    }
};

// Fetch notifications when the component is mounted
onMounted(() => {
    fetchNotifications();
});

</script>