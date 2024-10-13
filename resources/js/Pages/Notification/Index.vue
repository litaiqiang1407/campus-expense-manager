<template>
    <div>
        <div v-if="loading" class="h-screen flex items-center justify-center"><Loading  /></div>
        <div v-if="notifications.length == 0 && !loading">
            <NoData :message="'No notifications'" />
        </div>
        <div v-else>
            <div class="">
                <div v-for="notification in notifications" :key="notification.id" 
                    :class="['p-4 border', notification.is_read ? 'bg-gray-100' : 'bg-white']">
                    <div class="flex justify-between items-center">
                        <h3 class="text-lg font-semibold">{{ notification.title }}</h3>
                        <span class="capitalize" :class="notificationTypeClass(notification.type)">
                            {{ notification.type }}
                        </span>
                    </div>
                    <p class="text-sm text-gray-600 mt-1">{{ notification.message }}</p>
                    <p class="text-xs text-gray-400 mt-1">{{ formatDate(notification.created_at) }}</p>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import NoData from '@/Components/NoData/Index.vue';
import { ref, onMounted } from 'vue';
import { format } from 'date-fns';
import Loading from '@/Components/Loading/Index.vue';

const notifications = ref([]); 
const loading = ref(false);

const fetchNotifications = async () => {
    try {
        loading.value = true;
        const response = await axios.get(route('Notification')); 
        notifications.value = response.data;
    } catch (error) {
        console.error('Error fetching notifications:', error);
    } finally {
        loading.value = false;
    }
};

const formatDate = (dateString) => {
    return format(new Date(dateString), 'PPpp');
};

// Dynamically assign a class based on the notification type
const notificationTypeClass = (type) => {
    switch (type) {
        case 'success':
            return 'text-green-500';
        case 'error':
            return 'text-red-500';
        case 'info':
            return 'text-blue-500';
        case 'warning':
            return 'text-yellow-500';
        default:
            return 'text-gray-500';
    }
};

onMounted(() => {
    fetchNotifications();
});

</script>