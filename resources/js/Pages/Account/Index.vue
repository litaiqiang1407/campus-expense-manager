<template>
    <div class="bg-white">
        <div class=" flex items-center justify-center" @click="goPage('MyAccount')">
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
            <div class=" absolute right-0 pt-8 pr-1 z-50">
            <font-awesome-icon icon="angle-right" class="text-secondaryText size-5" />
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

        <div class="text-center p-1 mb-2">
            <h2 class="text-sm font-semibold text-black">{{  account.name  }}</h2>
            <p class="text-xs text-secondaryText">{{  account.email  }}</p>
        </div>
        <div class="h-28 bg-gray-200 border border-gray-300 flex items-center justify-center text-center">
            <video class="w-full h-full object-cover" autoplay controls loop>
                <source src="/assets/video/demovideo.mp4" type="video/mp4" />
                Your browser does not support the video tag.
            </video>
        </div>

        <div class=" w-full max-w-md mx-auto p-4 md:p-6 lg:p-8">
            <!-- My Wallets -->
            <div class="flex items-center justify-between py-2" @click="goPage('MyWallet')">
                <div class="flex items-center space-x-3">
                    <font-awesome-icon icon="wallet" class="text-secondaryText size-5 px-2" />
                    <span class="text-black font-medium text-sm">My Wallets</span>
                </div>
                <div class="right-0 pr-1">
                    <font-awesome-icon icon="angle-right" class="text-secondaryText size-4" />
                </div>
            </div>
            <!-- Categories -->
            <div class="flex items-center justify-between py-2">
                <div class="flex items-center space-x-3">
                    <font-awesome-icon icon="cubes" class="text-secondaryText size-5 px-2" />
                    <span class="text-black font-medium text-sm">Categories</span>
                </div>
                <div class="right-0 pr-1">
                    <font-awesome-icon icon="angle-right" class="text-secondaryText size-4" />
                </div>
            </div>
            <!-- Connect to banks (Highlighted Button) -->
            <div class="flex items-center justify-center py-4">
                <button class="bg-green-100 text-primary font-semibold px-4 py-1 rounded-3xl w-full text-center shadow-sm">
                    Connect to banks
                </button>
            </div>
             <!-- Recurring Transactions -->
            <div class="flex items-center justify-between py-2">
                <div class="flex items-center space-x-3">
                    <font-awesome-icon icon="money-bills" class="text-secondaryText size-5 px-2" />
                    <span class="text-black font-medium text-sm">Recurring Transactions</span>
                </div>
                <div class="right-0 pr-1">
                    <font-awesome-icon icon="angle-right" class="text-secondaryText size-4" />
                </div>
            </div>
             <!-- Debt -->
            <div class="flex items-center justify-between py-2">
                <div class="flex items-center space-x-3">
                    <font-awesome-icon icon="hand-holding-dollar" class="text-secondaryText size-5 px-2" />
                    <span class="text-black font-medium text-sm">Debt</span>
                </div>
                <div class="right-0 pr-1">
                    <font-awesome-icon icon="angle-right" class="text-secondaryText size-4" />
                </div>
            </div>
             <!-- Settings -->
            <div class="flex items-center justify-between py-2">
                <div class="flex items-center space-x-3">
                    <font-awesome-icon icon="gear" class="text-secondaryText size-5 px-2" />
                    <span class="text-black font-medium text-sm">Settings</span>
                </div>
                <div class="right-0 pr-1">
                    <font-awesome-icon icon="angle-right" class="text-secondaryText size-4" />
                </div>
            </div>
             <!-- About -->
            <div class="flex items-center justify-between py-2" @click="goPage('AppInfo')">
                <div class="flex items-center space-x-3">
                    <font-awesome-icon icon="circle-info" class="text-secondaryText size-5 px-2" />
                    <span class="text-black font-medium text-sm">About</span>
                </div>
                <div class="right-0 pr-1">
                    <font-awesome-icon icon="angle-right" class="text-secondaryText size-4" />
                </div>
            </div>
        </div>
    </div>

</template>


<script setup>
import { ref, onMounted } from 'vue';
import { useRouter } from 'vue-router';

const router = useRouter();

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
onMounted(() => {
    fetchAccount();
});

const goPage = (page) => {
    router.push({ name: page });
};
</script>
