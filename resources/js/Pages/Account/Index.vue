<template>
    <!-- User info -->
    <div class="bg-white">      
        <div class=" flex items-center justify-center">
            <!-- Avatar Circle -->
            <div
                class="flex items-center justify-center w-16 h-16 bg-teal-400 text-white text-2xl font-bold rounded-full absolute z-0">
                T
            </div>
    
            <!-- Account Badge -->
            <div class="flex items-center pt-16 relative z-40">
                <div class="bg-gray-500 text-white text-xs px-3 py-1 shadow whitespace-nowrap">BASIC ACCOUNT</div>
            </div>
    
            <div class="flex items-center pt-12 absolute z-20">
                <!-- Left Icon -->
                <div class="w-6 h-6 mr-12 relative z-40">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-full h-full fill-gray-400" viewBox="0 0 24 24">
                        <rect fill-opacity="0" fill="#FFFFFF" x="0" y="0" width="24" height="24"></rect>
                        <polygon fill="#999999" points="8 10 0 0 24 0 24 20 0 20"></polygon>
                        <polygon fill="#606060"
                            transform="translate(20.000000, 4.000000) scale(1, -1) translate(-20.000000, -4.000000)"
                            points="16 0 24 8 24 0"></polygon>
                    </svg>
                </div>
    
                <!-- Right Icon -->
                <div class="w-6 h-6 ml-12 relative z-40">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-full h-full fill-gray-400" viewBox="0 0 24 24">
                        <rect fill-opacity="0" fill="#FFFFFF" x="0" y="0" width="24" height="24"></rect>
                        <polygon fill="#999999"
                            transform="translate(12.000000, 10.000000) scale(-1, 1) translate(-12.000000, -10.000000)"
                            points="8 10 0 0 24 0 24 20 0 20"></polygon>
                        <polygon fill="#606060"
                            transform="translate(4.000000, 4.000000) scale(-1, -1) translate(-4.000000, -4.000000)"
                            points="0 0 8 8 8 0"></polygon>
                    </svg>
                </div>
            </div>
        </div>
        <!-- User info -->
        <div class="text-center p-1">
            <h2 class="text-sm font-semibold text-gray-800">Tranvuca213</h2>
            <p class="text-xs text-gray-500">tranvuca213@gmail.com</p>
        </div>
        <!-- Poster -->
        <div class="h-28 bg-gray-200 border border-gray-300 flex items-center justify-center text-center">
            <video class="w-full h-full object-cover" controls>
                <source src="/public/assets/video/demovideo.mp4" type="video/mp4" />
                Your browser does not support the video tag.
            </video>
        </div>
    
        <div class="bg-white w-full max-w-sm p-4">
            <!-- My Wallets -->
            <div class="flex items-center justify-between py-2">
                <div class="flex items-center space-x-3">
                    <font-awesome-icon icon="wallet" class="" />
                    <span class="text-gray-800 text-sm">My Wallets</span>
                </div>
            </div>
        </div>
    </div>
</template>


<script setup>
import { ref, onMounted } from 'vue';

const account = ref([])

const fetchAccount = async () => {
    try {
        const response = await axios.get(route('Account')); 
        account.value = response.data;
        console.log('Account:', account.value);
    } catch (error) {
        console.error('Error fetching Account:', error);
    }
};

onMounted(() => {
    fetchAccount();
});
</script>