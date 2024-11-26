<template>
    <div class="flex items-center justify-center ">
        <!-- Avatar Circle -->
        <div
                class="flex items-center justify-center w-16 h-16 border-[1px] rounded-full absolute z-0">
                <template v-if="isLoading">
                    <div class="animate-spin rounded-full border-4 border-primary border-t-transparent w-16 h-16"></div>
                </template>
                <template v-else>
                    <img class="w-full h-full rounded-full" :src="account.avatar" alt="Avatar" referrerPolicy="no-referrer" />
                </template>
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
    <div class="text-center p-1 mb-2">
            <h2 class="text-sm font-semibold text-black">{{  account.name  }}</h2>
            <p class="text-xs text-secondaryText">{{  account.email  }}</p>
    </div>

    <div class="bg-white w-screen p-4">
    <div class="flex items-center justify-between py-2">
            <div class="flex items-center space-x-3">
                <font-awesome-icon icon="repeat" class="text-secondaryText size-5 px-2" />
                <span class="text-black font-medium text-sm">Change password</span>
            </div>
            <div>
                <font-awesome-icon icon="angle-right" class="text-secondaryText size-4 pr-3" />
            </div>
        </div>
        
        <div class="flex items-center justify-center py-4">
            <button @click="logout" class="button-animate bg-red-100 text-redText font-semibold px-4 py-1 rounded-3xl w-full text-center shadow-sm">
                Sign out
            </button>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';

const account = ref({});
const isLoading = ref(true); 

const fetchAccount = async () => {
    try {
        const response = await axios.get(route('Account')); 
        account.value = response.data; 
    } catch (error) {
        console.error('Error fetching Account:', error);
    } finally {
        isLoading.value = false; 
    }
};

const logout = () => {
    axios.post(route('Logout'))
        .then(() => {
            window.location.href = route('Welcome');
        })
        .catch((error) => {
            console.error('Error logging out:', error);
        });
};

onMounted(() => {
    fetchAccount();
});
</script>